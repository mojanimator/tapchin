<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Telegram;
use App\Http\Helpers\Util;
use App\Http\Helpers\Variable;
use App\Http\Requests\TicketRequest;
use App\Models\Notification;
use App\Models\Ticket;
use App\Models\TicketChat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function edit(Request $request, $ticket)
    {
        $user = auth()->user();
        $data = Ticket::whereId($ticket)->with('chats.owner:id,fullname,role')->first();
        $this->authorize('edit', [User::class, $data]);
        if ($user->isAdmin())
            $data->chats()->where('admin_seen', false)->update(['admin_seen' => true]);
        else
            $data->chats()->where('user_seen', false)->update(['user_seen' => true]);
        $attachments = array_map(fn($e) => basename($e), File::glob(Storage::path("public/" . Variable::IMAGE_FOLDERS[Ticket::class]) . "/$data->id/*"));

        return Inertia::render('Panel/Ticket/Edit', [
            'statuses' => Variable::TICKET_STATUSES,
            'data' => $data,
            'attachments' => $attachments,
            'attachment_allowed_mimes' => implode(',.', Variable::TICKET_ATTACHMENT_ALLOWED_MIMES),
        ]);
    }

    public function update(TicketRequest $request)
    {
        $user = auth()->user();
//        $response = ['message' => __('done_successfully')];
        $response = ['message' => __('response_error')];
        $errorStatus = Variable::ERROR_STATUS;
        $successStatus = Variable::SUCCESS_STATUS;

        $id = $request->id;
        $cmnd = $request->cmnd;
        $data = Ticket::find($id);
        if (!starts_with($cmnd, 'bulk'))
            $this->authorize('update', [User::class, $data]);

        if ($cmnd) {
            switch ($cmnd) {
                case 'close':
                    $data->status = 'closed';
                    $data->save();
                    $res = ['status' => 'closed', 'flash_status' => 'success', 'flash_message' => __('updated_successfully')];
                    return back()->with($res);

                case 'del-chat':
                    $data = Ticket::whereId($request->ticket_id)->with('chats.owner:id,fullname,role')->first();
                    $chats = $data->getRelation('chats');
                    if (!$data || !$chats)
                        return back()->withErrors(['flash_message' => __('item_not_found')]);
                    foreach ($chats as $idx => $chat) {
                        if ($chat->id == $request->chat_id) {
                            foreach (File::glob(Storage::path("public/" . Variable::IMAGE_FOLDERS[Ticket::class]) . "/$data->id/$chat->id-*") as $path) {
                                File::delete($path);
                            }

                            $chat->delete();
                            $chats->forget($idx);
                            $len = count($chats);
                            if ($len == 0)
                                $data->status = 'closed';
                            elseif ($len > 0 && User::firstOrNew(['id' => $chats->first()->from_id])->isAdmin())
                                $data->status = 'responded';
                            else
                                $data->status = 'review';
                            $data->save();
                        }
                    }
                    $res = ['chats' => $chats, 'status' => $data->status, 'flash_status' => 'success', 'flash_message' => __('updated_successfully')];
                    return back()->with($res);
                    break;
                case 'add-chat':
                    $data->status = $user->isAdmin() ? 'responded' : 'review';
                    $ticketChat = TicketChat::create([
                        'ticket_id' => $data->id,
                        'from_id' => $user->id,
                        'user_seen' => !$user->isAdmin(),
                        'admin_seen' => $user->isAdmin(),
                        'message' => $request->message,
                    ]);
                    foreach ($request->file('attachments') ?? [] as $idx => $item)
                        if ($item)
                            Util::createFile($item, Variable::IMAGE_FOLDERS[Ticket::class] . "/$data->id", "$ticketChat->id-$idx");
                    $data->updated_at = Carbon::now();
                    $data->save();
                    $res = ['chats' => $data->chats, 'status' => $data->status, 'flash_status' => 'success', 'flash_message' => __('updated_successfully')];
                    if ($user->isAdmin()) {
                        Notification::create([
                            'data_id' => $data->id,
                            'owner_id' => $data->owner_id,
                            'subject' => __('ticket_answer'),
                            'description' => __('ticket_answered'),
                            'type' => 'ticket_answer',

                        ]);
                        User::where('id', $data->owner_id)->increment('notifications');
                    }
                    Telegram::log(null, 'ticket_updated', $data);
                    return back()->with($res);


            }
        } elseif ($data) {


            $request->merge([
            ]);
//            $data->name = $request->tags;
//            $data->tags = $request->tags;
//            dd($request->tags);
            if ($data->update($request->all())) {

                $res = ['flash_status' => 'success', 'flash_message' => __('updated_successfully')];
//                dd($request->all());
                Telegram::log(null, 'ticket_edited', $data);
            } else    $res = ['flash_status' => 'danger', 'flash_message' => __('response_error')];
            return back()->with($res);
        }

        return response()->json($response, $errorStatus);
    }

    public function searchPanel(Request $request)
    {
        $user = $request->user();
        $search = $request->search;
        $page = $request->page ?: 1;
        $orderBy = $request->order_by ?: 'id';
        $dir = $request->dir ?: 'DESC';
        $paginate = $request->paginate ?: 24;

        $query = Ticket::query();
        $query = $query->select();
        if ($user->role == 'us')
            $query = $query->where('owner_id', $user->id);

        if ($search)
            $query = $query->where('subject', 'like', "%$search%");

        return $query->orderBy($orderBy, $dir)->paginate($paginate, ['*'], 'page', $page);
    }

    public function create(TicketRequest $request)
    {


        $user = auth()->user()/* ?? auth('api')->user()*/
        ;

        if (!$user) {
            $res = ['flash_status' => 'danger', 'flash_message' => __('response_error')];
            return back()->with($res);
        }
//        if ($user->is_block) {
//            $res = ['flash_status' => 'danger', 'flash_message' => __('user_is_blocked')];
//            return back()->with($res);
//        }
        if (!$request->uploading) { //send again for uploading images

            return back()->with(['resume' => true]);
        }


        $ticket = Ticket::create([
            'subject' => $request->subject,
            'owner_id' => $user->id,
            'status' => $user->role != 'us' ? 'responded' : 'review',
        ]);

        if ($ticket) {
            $res = ['flash_status' => 'success', 'flash_message' => __('created_successfully')];

            $ticketChat = TicketChat::create([
                'ticket_id' => $ticket->id,
                'from_id' => $user->id,
                'user_seen' => true,
                'admin_seen' => false,
                'message' => $request->message,
            ]);
            foreach ($request->file('attachments') ?? [] as $idx => $item)
                if ($item)
                    Util::createFile($item, Variable::IMAGE_FOLDERS[Ticket::class] . "/$ticket->id", "$ticketChat->id-$idx");

            Telegram::log(null, 'ticket_created', $ticket);
        } else    $res = ['flash_status' => 'danger', 'flash_message' => __('response_error')];
        return to_route('panel.ticket.index')->with($res);
    }
}
