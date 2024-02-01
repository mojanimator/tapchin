<?php

namespace App\Http\Helpers;


use App\Models\Category;
use App\Models\City;
use App\Models\Site;
use App\Models\User;
use DateTimeZone;
use   Illuminate\Support\Facades\Http;
use Morilog\Jalali\Jalalian;

class Telegram
{
    static function sendMessage($chat_id, $text, $mode = null, $reply = null, $keyboard = null, $disable_notification = false)
    {
        return self::creator('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => $mode,
            'reply_to_message_id' => $reply,
            'reply_markup' => $keyboard,
            'disable_notification' => $disable_notification,
        ]);
    }

    static function deleteMessage($chatid, $massege_id)
    {
        return self::creator('DeleteMessage', [
            'chat_id' => $chatid,
            'message_id' => $massege_id
        ]);
    }

    static function sendPhoto($chat_id, $photo, $caption, $reply = null, $keyboard = null)
    {
        if (!str_contains(url('/'), '.com') && !str_contains(url('/'), '.ir')) return;

        return self::creator('sendPhoto', [
            'chat_id' => $chat_id,
            'photo' => $photo,
            'caption' => /*self::MarkDown($caption)*/ $caption,
            'parse_mode' => /*'Markdown'*/ null,
            'reply_to_message_id' => $reply,
            'reply_markup' => $keyboard
        ]);

        $response = json_decode($res->body());
        $response->url = $url;
        $response->photo = $photo;
//        self::sendMessage(Variable::$logs[0], json_encode($response));
        return $response;
    }


    static function sendMediaGroup($chat_id, $media, $keyboard = null, $reply = null)
    {
//2 to 10 media can be send

        return self::creator('sendMediaGroup', [
            'chat_id' => $chat_id,
            'media' => json_encode($media),
            'reply_to_message_id' => $reply,

        ]);

    }

    static function sendSticker($chat_id, $file_id, $keyboard, $reply = null, $set_name = null)
    {
        return self::creator('sendSticker', [
            'chat_id' => $chat_id,
            'sticker' => $file_id,
            "set_name" => $set_name,
            'reply_to_message_id' => $reply,
            'reply_markup' => $keyboard
        ]);
    }


    static function logAdmins($msg, $mode = null)
    {
        $res = null;
        foreach (Variable::LOGS as $log)
            $res = self::sendMessage($log, $msg, $mode);
        return $res;

    }

    static function creator($method, $datas = [])
    {
        if (!str_contains(url('/'), '.com') && !str_contains(url('/'), '.ir')) return;
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN', '') . "/" . $method;

//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
//        $res = curl_exec($ch);
////        self::sendMessage(Helper::$logs[0], $res);
//        if (curl_error($ch)) {
//            self::sendMessage(Variable::LOGS[0], curl_error($ch));
//            curl_close($ch);
//            return (curl_error($ch));
//        } else {
//            curl_close($ch);
//            return json_decode($res);
//        }


        $res = Http::asForm()->post($url, $datas);
        if ($res->status() != 200)
            self::sendMessage(Variable::LOGS[0], $res->body() . PHP_EOL . print_r($datas, true));
        return json_decode($res->body());


    }

    public
    static function MarkDown($string)
    {
        $string = str_replace(["_",], '\_', $string);
        $string = str_replace(["`",], '\`', $string);
        $string = str_replace(["*",], '\*', $string);
        $string = str_replace(["~",], '\~', $string);


        return $string;
    }

    public
    static
    function popupMessage2($data_id, $from_id, $message)
    {
        return self::creator('CallbackQuery', [
            'id' => $data_id,
            'from' => $from_id,
            'message' => $message,

        ]);
    }

    public static
    function popupMessage($data_id, $text)
    {
        return self::creator('answerCallbackQuery', [
            'callback_query_id' => $data_id,
            'text' => $text,

            'show_alert' => true, # popup / notification
            'url' => null,# t.me/your_bot?start=XXXX,
            'cache_time' => null
        ]);
    }


    public static
    function inviteToChat($chat_id)
    {

        return self::creator('exportChatInviteLink', ['chat_id' => $chat_id,]);

    }

    public static
    function getChatMembersCount($chat_id)
    {
        $res = self::creator('getChatMembersCount', ['chat_id' => $chat_id,])->result;
        if ($res)
            return (int)$res; else return 0;
    }

    public static
    function getChatInfo($chat_id)
    {
        return self::creator('getChat', ['chat_id' => $chat_id]);
    }

    public static
    function admin($chat_id, $from_id, $chat_type, $chat_username)
    {
        if ($chat_type == 'supergroup' || $chat_type == 'group') {
            $get = self::creator('getChatMember', ['chat_id' => $chat_id, 'user_id' => $from_id]);
            $rank = $get->result->status;

            if ($rank == 'creator' || $rank == 'administrator') {
                return true;
            } else {
//                $this->sendMessage($chat_id, "■  کاربر غیر مجاز \n $this->bot  ", 'MarkDown', null);
                return false;
            }
        } else if ($chat_type == 'channel') {


            return true;
//            $admins = self::creator('getChatAdministrators', ['chat_id' => $chat_id])->result;
//            $is_admin = false;
//
//            foreach ($admins as $admin) {
//                if ($from_id == $admin->user->id) {
//                    $is_admin = true;
//                }
//            }
//            return $from_id;

//            $this->user = User::whereIn('telegram_id', $admin_ids)->orWhere('channels', 'like', "%[$chat_username,%")
//                ->orWhere('channels', 'like', "%,$chat_username,%")
//                ->orWhere('channels', 'like', "%,$chat_username]%")->first();
//            if (!User::orWhere('channels', 'like', "%[$chat_username,%")
//                ->orWhere('channels', 'like', "%,$chat_username,%")
//                ->orWhere('channels', 'like', "%,$chat_username]%")->exists())
//                $this->sendMessage($chat_id, "■ ابتدا کانال را در ربات ثبت نمایید  \n📣$this->bot  ", 'MarkDown', null);


//            return $this->user ? true : false;
        }
    }

    public static
    function get_chat_type($chat_id)
    {

        return self::creator('getChat', [
            'chat_id' => $chat_id,

        ])->result->type;
    }

    public static
    function user_in_chat($chat_id, $user_id, $chat_type = null)
    {
        return self::creator('getChatMember', [
            'chat_id' => $chat_id,
            'user_id' => $user_id
        ])->result->status;
    }

    public static
    function editMessageText($chat_id, $message_id, $text, $mode = null, $keyboard = null)
    {
        self::creator('EditMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $text,
            'parse_mode' => $mode,
            'reply_markup' => $keyboard
        ]);
    }

    public static
    function editMessageCaption($chat_id, $message_id, $text, $mode = null, $keyboard = null)
    {
        self::creator('editMessageCaption', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'caption' => $text,
            'parse_mode' => $mode,
            'reply_markup' => $keyboard
        ]);
    }

    public static
    function editKeyboard($chat_id, $message_id, $keyboard)
    {
        self::creator('EditMessageReplyMarkup', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'reply_markup' => $keyboard
        ]);
    }

    public static
    function kick($chatid, $fromid)
    {
        self::creator('KickChatMember', [
            'chat_id' => $chatid,
            'user_id' => $fromid
        ]);
    }

    public static
    function forward($chatid, $from_id, $massege_id)
    {
        self::creator('ForwardMessage', [
            'chat_id' => $chatid,
            'from_chat_id' => $from_id,
            'message_id' => $massege_id
        ]);
    }

    public static
    function sendFile($chat_id, $storage, $reply = null)
    {


        $message = json_decode($storage);
        $poll = $message->poll;
        $text = $message->text;
        $sticker = $message->sticker;  #width,height,emoji,set_name,is_animated,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,
        $animation = $message->animation;  #file_name,mime_type,width,height,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,

        $photo = $message->photo; # file_id,file_unique_id,file_size,width,height] array of different photo sizes
        $document = $message->document; #file_name,mime_type,thumb[file_id,file_unique_id,file_size,width,height]
        $video = $message->video; #duration,width,height,mime_type,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,height]
        $audio = $message->audio; #duration,mime_type,title,performer,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,height]
        $voice = $message->voice; #duration,mime_type,file_id,file_unique_id,file_size
        $video_note = $message->video_note; #duration,length,file_id,file_unique_id,file_size,thumb[file_id,file_unique_id,file_size,width,height]
        $caption = $message->caption;

        if ($text) {
            $adv_section = explode('banner=', $text); //banner=name=@id
            $text = $adv_section[0];
        } else if ($caption) {
            $adv_section = explode('banner=', $caption);
            $caption = $adv_section[0];
        }
        if (count($adv_section) == 2) {

            $link = explode('=', $adv_section[1]);
            $trueLink = $link[1];
            foreach ($link as $idx => $li) {
                if ($idx > 1)
                    $trueLink .= ('=' . $li);
            }
            $buttons = [[['text' => "👈 $link[0] 👉", 'url' => $trueLink]]];
        } else {
//            if ($text) $text = $text ;  //. "\n\n" . $this->bot;
//            else if ($caption) $caption = $caption . "\n\n" . $this->bot;
            $buttons = null;
        }
        $keyboard = null;
        if ($buttons)
            $keyboard = json_encode(['inline_keyboard' => $buttons, 'resize_keyboard' => true]);

        if ($text)
            self::creator('SendMessage', [
                'chat_id' => $chat_id,
                'text' => $text, //. "\n $this->bot",
                'parse_mode' => 'Markdown',
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        else if ($photo)
            self::creator('sendPhoto', [
                'chat_id' => $chat_id,
                'photo' => $photo[count($photo) - 1]->file_id,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        else if ($audio)
            self::creator('sendAudio', [
                'chat_id' => $chat_id,
                'audio' => $audio->file_id,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'duration' => $audio->duration,
                'performer' => $audio->performer,
                'title' => $audio->title,
                'thumb' => $audio->thumb,
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        else if ($document)
            self::creator('sendDocument', [
                'chat_id' => $chat_id,
                'document' => $document->file_id,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'thumb' => $document->thumb,
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        else if ($video)
            self::creator('sendVideo', [
                'chat_id' => $chat_id,
                'video' => $video->file_id,
                'duration' => $video->duration,
                'width' => $video->width,
                'height' => $video->height,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'thumb' => $video->thumb,
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        else if ($animation)
            self::creator('sendAnimation', [
                'chat_id' => $chat_id,
                'animation' => $animation->file_id,
                'duration' => $animation->duration,
                'width' => $animation->width,
                'height' => $animation->height,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'thumb' => $animation->thumb,
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        else if ($voice)
            self::creator('sendVoice', [
                'chat_id' => $chat_id,
                'voice' => $voice->file_id,
                'duration' => $voice->duration,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        else if ($video_note)
            self::creator('sendVideoNote', [
                'chat_id' => $chat_id,
                'video_note' => $video_note->file_id,
                'duration' => $video_note->duration,
                'length' => $video_note->length,
                'thumb' => $video_note->thumb,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        else if ($sticker)
            self::creator('sendSticker', [
                'chat_id' => $chat_id,
                'sticker' => $sticker->file_id,
                "set_name" => "DaisyRomashka",
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
        else if ($poll)
            self::creator('sendPoll', [
                'chat_id' => $chat_id,
                'question' => "",
                'options' => json_encode(["1", "2", "3"]),
                'type' => "regular",//quiz
                'allows_multiple_answers' => false,
                'correct_option_id' => 0, // index of correct answer for quiz
//            'open_period' => 5-600,   this or close_date
//            'close_date' => 5, 5 - 600,
                'reply_to_message_id' => $reply,
                'reply_markup' => $keyboard
            ]);
    }

    static function log($to, $type, $data)
    {

        try {

            if ($data instanceof User)
                $us = $data;
            elseif (isset($data->owner_id))
                $us = User::find($data->owner_id);
            elseif (isset($data->user_id))
                $us = User::find($data->user_id);
            else
                $us = auth()->user();
            $user = auth('sanctum')->user();
            $admin = isset ($us) && (in_array($us->role, ['ad', 'go']));
            $now = Jalalian::forge('now', new DateTimeZone('Asia/Tehran'));
            $time = $now->format('%A, %d %B %Y ⏰ H:i');
            $msg = "\xD8\x9C" . config('app.name') . PHP_EOL . $time . PHP_EOL;
            $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;

            switch ($type) {
                case 'site_created':
                    $msg .= " 🟢 " . "یک سایت ساخته شد" . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نویسنده: " . PHP_EOL;
                    $msg .= ($us->fullname) . PHP_EOL;
                    $msg .= " 🚩 " . "زبان: " . $data->lang . PHP_EOL;
                    $msg .= " 🪧 " . "عنوان:" . PHP_EOL . $data->name . PHP_EOL;
                    $msg .= " 🔗 " . "لینک:" . PHP_EOL . $data->link . PHP_EOL;
                    $msg .= " 🚥 " . "دسته بندی: " . __(Category::findOrNew($data->category_id)->name) . PHP_EOL;
                    $msg .= " 🔖 " . "تگ ها:" . PHP_EOL . $data->tags . PHP_EOL;
                    $msg .= " 📜 " . "توضیحات:" . PHP_EOL . $data->description . PHP_EOL;
                    $msg .= " 🖼 " . "تصویر:" . PHP_EOL . route('storage.sites') . "/$data->id.jpg" . PHP_EOL;

                    break;
                case 'site_edited':
                    $msg .= " 🟠 " . "یک سایت ویرایش شد" . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نویسنده: " . PHP_EOL;
                    $msg .= ($us->fullname) . PHP_EOL;
                    $msg .= " 🚩 " . "زبان: " . $data->lang . PHP_EOL;
                    $msg .= " 🪧 " . "عنوان:" . PHP_EOL . $data->name . PHP_EOL;
                    $msg .= " 🔗 " . "لینک:" . PHP_EOL . $data->link . PHP_EOL;
                    $msg .= " 🚥 " . "دسته بندی: " . __(Category::findOrNew($data->category_id)->name) . PHP_EOL;
                    $msg .= " 🔖 " . "تگ ها:" . PHP_EOL . $data->tags . PHP_EOL;
                    $msg .= " 📜 " . "توضیحات:" . PHP_EOL . $data->description . PHP_EOL;
                    $msg .= " 🖼 " . "تصویر:" . PHP_EOL . route('storage.sites') . "/$data->id.jpg" . PHP_EOL;

                    break;
                case 'contact_created':
                    $contact = new Contact();
                    $contact = $data;
                    $user = \App\Models\User::firstOrNew(['id' => $data->user_id]);
                    $msg .= " 🟢 " . "یک پیام ثبت شد" . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نویسنده: " . PHP_EOL;
                    $msg .= ($user->fullname) . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس" . PHP_EOL;
                    $msg .= $user->mobile . PHP_EOL;
                    $msg .= " 📧 " . "ایمیل: " . PHP_EOL;
                    $msg .= $user->email . PHP_EOL;
                    $msg .= " 📃 " . "عنوان" . PHP_EOL;
                    $msg .= $contact->title . PHP_EOL;
                    $msg .= " 📌 " . "متن" . PHP_EOL;
                    $msg .= $contact->text . PHP_EOL;

                    break;
                case 'user_created':

                    $msg .= "یک کاربر ساخته شد" . PHP_EOL;
                    $msg .= "مارکت: " . $data->market . PHP_EOL;
                    $msg .= "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . $data->fullname . PHP_EOL;
                    $msg .= " 👤 " . $data->username . PHP_EOL;
                    $msg .= " 📱 " . $data->phone . PHP_EOL;
                    $msg .= " 📧 " . $data->email . PHP_EOL;
                    break;
                case 'user_created':

                    $msg .= " 🟢 " . "یک کاربر ساخته شد" . PHP_EOL;
                    $msg .= " 🆔 " . "شناسه: " . $data->user_id . PHP_EOL;
                    $msg .= " 🚩 " . ($data->is_lawyer ? "وکیل " : "کاربر عادی") . PHP_EOL;
                    $msg .= " 👤 " . "نام " . PHP_EOL;
                    $msg .= $data->fullname . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس" . PHP_EOL;
                    $msg .= $data->phone . PHP_EOL;
                    $msg .= " 📧 " . "ایمیل: " . PHP_EOL;
                    $msg .= $data->email . PHP_EOL;
                    break;
                case 'transaction_created':
                    if ($data->amount > 0)
                        $msg .= " 🟢🟢🟢🛒 " . "یک تراکنش انجام شد" . PHP_EOL;
                    else
                        $msg .= " 🟠🟠🟠🛒 " . "یک پلن خریداری شد" . PHP_EOL;
                    $msg .= " 🆔 " . "شناسه کاربر: " . $us->id . PHP_EOL;
                    $msg .= " 👤 " . "نام " . PHP_EOL;
                    $msg .= $us->fullname . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس" . PHP_EOL;
                    $msg .= $us->phone . PHP_EOL;
                    $msg .= " ⭐ " . "نوع" . PHP_EOL;
                    $msg .= $data->title . PHP_EOL;
                    $msg .= " 📊 " . "مقدار" . PHP_EOL;
                    $msg .= $data->amount . PHP_EOL;

                    break;
                case 'setting_created':
                case 'setting_updated':
                case 'setting_deleted':
                    if ($type == 'setting_created')
                        $msg .= " 🟢 " . "یک تنظیمات ساخته شد" . PHP_EOL;
                    if ($type == 'setting_updated')
                        $msg .= " 🟠 " . "یک تنظیمات ویرایش شد" . PHP_EOL;
                    if ($type == 'setting_deleted')
                        $msg .= " 🔴 " . "یک تنظیمات حذف شد" . PHP_EOL;
                    $msg .= " *️⃣ " . $data->key . PHP_EOL;
                    $msg .= " #️⃣ " . $data->value . PHP_EOL;
                    break;
                case 'video_created':

                    $msg .= " 🟢 " . "یک ویدیو ثبت شد" . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نویسنده: " . PHP_EOL;
                    $msg .= ($us->fullname) . PHP_EOL;
                    $msg .= " 📃 " . "عنوان" . PHP_EOL;
                    $msg .= $data->name . PHP_EOL;
                    $msg .= " ⭐ " . "دسته" . PHP_EOL;
                    $msg .= Category::findOrNew($data->category_id)->name . PHP_EOL;
                    $msg .= route('storage.videos') . '/' . $data->id . '.jpg' . '?r=' . random_int(10, 1000) . PHP_EOL;
                    $msg .= route('storage.videos') . '/' . $data->id . '.mp4' . '?r=' . random_int(10, 1000) . PHP_EOL;
                    $msg .= " 📌 " . url('video') . "/$data->id" . PHP_EOL;
                    break;
                case 'video_edited':
                    $msg .= " 🟢 " . "یک ویدیو ویرایش شد" . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نویسنده: " . PHP_EOL;
                    $msg .= ($us->fullname) . PHP_EOL;
                    $msg .= " 📃 " . "عنوان" . PHP_EOL;
                    $msg .= $data->name . PHP_EOL;
                    $msg .= " ⭐ " . "دسته" . PHP_EOL;
                    $msg .= Category::findOrNew($data->category_id)->name . PHP_EOL;
                    $msg .= route('storage.videos') . '/' . $data->id . '.jpg' . '?r=' . random_int(10, 1000) . PHP_EOL;
                    $msg .= route('storage.videos') . '/' . $data->id . '.mp4' . '?r=' . random_int(10, 1000) . PHP_EOL;
                    $msg .= " 📌 " . url('video') . "/$data->id" . PHP_EOL;
                    break;
                    break;
                case 'agency_created':
                    $msg .= " 🟢 " . "یک نمایندگی ساخته شد" . PHP_EOL;
                    $msg .= " 📅 " . "تاریخ : " . PHP_EOL . Jalalian::fromDateTime($data->updated_at)->format('%Y/%m/%d ⏰ H:i') . PHP_EOL;
                    $msg .= " 👤 " . "سازنده" . PHP_EOL;
                    $msg .= optional($user)->fullname . PHP_EOL;
                    $parent = \App\Models\Agency::find($data->parent_id);
                    if ($parent) {
                        $msg .= " 👤 " . "نمایندگی والد" . PHP_EOL;
                        $msg .= ($parent->name . ' 📱 ' . $parent->phone) . PHP_EOL;
                    }
                    $msg .= " 👤 " . "مالک" . PHP_EOL;
                    $owner = \App\Models\User::findOrNew($data->owner_id);
                    $msg .= ($owner->name ? "$owner->name $owner->family" : "$owner->username") . PHP_EOL;
                    $msg .= " 📌 " . "نام نمایندگی" . PHP_EOL;
                    $msg .= $data->name . PHP_EOL;
                    $msg .= " 🚩 " . "استان: " . City::firstOrNew(['id' => $data->province_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "شهر: " . City::firstOrNew(['id' => $data->county_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "آدرس: " . $data->address . PHP_EOL;
                    $msg .= " 📧 " . "ایمیل: " . PHP_EOL;
                    $msg .= $data->email . PHP_EOL;
                    $msg .= " 📱 " . "توضیحات: " . $data->description . PHP_EOL;

                    break;

                case 'agency_edited':
                    $msg .= " 🟧 " . " $attribute " . "یک نمایندگی ویرایش شد" . PHP_EOL;
                    $msg .= " 📅 " . "تاریخ : " . PHP_EOL . Jalalian::fromDateTime($data->updated_at)->format('%Y/%m/%d ⏰ H:i') . PHP_EOL;
                    $msg .= " 👤 " . "سازنده" . PHP_EOL;
                    $msg .= ($us->name ? "$us->name $us->family" : "$us->username") . PHP_EOL;
                    $msg .= " 👤 " . "مالک" . PHP_EOL;
                    $owner = \App\Models\User::findOrNew($data->owner_id);
                    $msg .= ($owner->name ? "$owner->name $owner->family" : "$owner->username") . PHP_EOL;
                    $msg .= " 📌 " . "نام نمایندگی" . PHP_EOL;
                    $msg .= $data->name . PHP_EOL;
                    $msg .= " 🚩 " . "استان: " . Province::firstOrNew(['id' => $data->province_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "شهر: " . County::firstOrNew(['id' => $data->county_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "آدرس: " . $data->address . PHP_EOL;
                    $msg .= " 📧 " . "ایمیل: " . PHP_EOL;
                    $msg .= $data->email . PHP_EOL;
                    $msg .= " 📱 " . "توضیحات: " . $data->description . PHP_EOL;

                    break;

                case 'table_created':
                    $msg .= " 🟢 " . "یک جدول ساخته شد" . PHP_EOL;
                    $msg .= " 👤 " . "سازنده" . PHP_EOL;
                    $msg .= ($us->name ? "$us->name $us->family" : "$us->username") . PHP_EOL;
                    $msg .= " 📌 " . "عنوان" . PHP_EOL;
                    $msg .= $data->title . PHP_EOL;
                    $msg .= " 📅 " . "تاریخ : " . PHP_EOL . Jalalian::fromDateTime($data->updated_at)->format('%Y/%m/%d ⏰ H:i') . PHP_EOL;
                    $msg .= " 🚩 " . "تورنومنت" . PHP_EOL;
                    $msg .= optional(Tournament::find($data->tournament_id))->name . PHP_EOL;


                    break;
                case 'event_created':
                    $msg .= " 🟢 " . "یک رویداد ساخته شد" . PHP_EOL;
                    $msg .= " 👤 " . "سازنده" . PHP_EOL;
                    $msg .= ($us->name ? "$us->name $us->family" : "$us->username") . PHP_EOL;
                    $msg .= " 📌 " . "عنوان" . PHP_EOL;
                    $msg .= $data->title . PHP_EOL;
                    $msg .= " 📅 " . "تاریخ : " . PHP_EOL . Jalalian::fromDateTime($data->time, new DateTimeZone('Asia/Tehran'))->format('%Y/%m/%d ⏰ H:i') . PHP_EOL;
                    $msg .= " 🚩 " . "آیتم 1" . PHP_EOL;
                    $msg .= $data->team1 . PHP_EOL;
                    $msg .= " 🚩 " . "آیتم 2" . PHP_EOL;
                    $msg .= $data->team2 . PHP_EOL;
                    $msg .= " 📊 " . "وضعیت" . PHP_EOL;
                    $msg .= $data->status . PHP_EOL;
                    $msg .= " ⭐ " . "دسته" . PHP_EOL;
                    $msg .= Sport::find($data->sport_id)->name . PHP_EOL;
                    $msg .= " 📃 " . "جزییات: " . PHP_EOL . $data->details . PHP_EOL;

                    break;
                case 'event_edited':
                    $msg .= " 🟢 " . "یک رویداد ویرایش شد" . PHP_EOL;
                    $msg .= " 👤 " . "سازنده" . PHP_EOL;
                    $msg .= ($us->name ? "$us->name $us->family" : "$us->username") . PHP_EOL;
                    $msg .= " 📌 " . "عنوان" . PHP_EOL;
                    $msg .= $data->title . PHP_EOL;
                    $msg .= " 📅 " . "تاریخ : " . PHP_EOL . Jalalian::fromDateTime($data->time, new DateTimeZone('Asia/Tehran'))->format('%Y/%m/%d ⏰ H:i') . PHP_EOL;
                    $msg .= " 🚩 " . "آیتم 1" . PHP_EOL;
                    $msg .= $data->team1 . PHP_EOL;
                    $msg .= " 🚩 " . "آیتم 2" . PHP_EOL;
                    $msg .= $data->team2 . PHP_EOL;
                    $msg .= " 📊 " . "وضعیت" . PHP_EOL;
                    $msg .= $data->status . PHP_EOL;
                    $msg .= " ⭐ " . "دسته" . PHP_EOL;
                    $msg .= Sport::find($data->sport_id)->name . PHP_EOL;
                    $msg .= " 📃 " . "جزییات: " . PHP_EOL . $data->details . PHP_EOL;

                    break;

                case 'player_created':
                    $msg .= " 🟡 " . "یک بازیکن ساخته شد" . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $docs = $data->docs;
                    foreach ($docs as $doc) {
                        $msg .= url('') . '/storage/' . $doc->type_id . '/' . $doc['id'] . '.' . ($doc['type_id'] == Helper::$docsMap['video'] ? 'mp4' : 'jpg') . '?r=' . random_int(10, 1000) . PHP_EOL;
                    }
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نام: " . PHP_EOL;
                    $msg .= $data->name . ' ' . $data->family . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس: " . PHP_EOL;
                    $msg .= $data->phone . PHP_EOL;
                    $msg .= " 🧬 " . "جنسیت: " . ($data->is_man ? 'مرد' : 'زن') . PHP_EOL;
                    $msg .= " 📅 " . "تاریخ تولد: " . Jalalian::fromDateTime($data->born_at)->format('%Y/%m/%d') . PHP_EOL;
                    $msg .= " 📏 " . "قد: " . $data->height . PHP_EOL;
                    $msg .= " ⚓ " . "وزن: " . $data->weight . PHP_EOL;
                    $msg .= " ⭐ " . "رشته ورزشی: " . Sport::firstOrNew(['id' => $data->sport_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "استان: " . Province::firstOrNew(['id' => $data->province_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "شهر: " . County::firstOrNew(['id' => $data->county_id])->name . PHP_EOL;
                    $msg .= " 📱 " . "توضیحات: " . $data->description . PHP_EOL;

                    break;

                case 'coach_created':
                    $msg .= " 🟠 " . "یک مربی ساخته شد" . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $docs = $data->docs;
                    foreach ($docs as $doc) {
                        $msg .= url('') . '/storage/' . $doc->type_id . '/' . $doc['id'] . '.' . ($doc['type_id'] == Helper::$docsMap['video'] ? 'mp4' : 'jpg') . '?r=' . random_int(10, 1000) . PHP_EOL;
                    }
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نام: " . PHP_EOL;
                    $msg .= $data->name . ' ' . $data->family . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس: " . PHP_EOL;
                    $msg .= $data->phone . PHP_EOL;
                    $msg .= " 🧬 " . "جنسیت: " . ($data->is_man ? 'مرد' : 'زن') . PHP_EOL;
                    $msg .= " 📅 " . "تاریخ تولد: " . Jalalian::fromDateTime($data->born_at)->format('%Y/%m/%d') . PHP_EOL;
                    $msg .= " ⭐ " . "رشته ورزشی: " . Sport::firstOrNew(['id' => $data->sport_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "استان: " . Province::firstOrNew(['id' => $data->province_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "شهر: " . County::firstOrNew(['id' => $data->county_id])->name . PHP_EOL;
                    $msg .= " 📱 " . "توضیحات: " . $data->description . PHP_EOL;

                    break;

                case 'club_created':
                    $msg .= " 🔵 " . "یک باشگاه ساخته شد" . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $docs = $data->docs;
                    foreach ($docs as $doc) {
                        $msg .= url('') . '/storage/' . $doc->type_id . '/' . $doc['id'] . '.' . ($doc['type_id'] == Helper::$docsMap['video'] ? 'mp4' : 'jpg') . '?r=' . random_int(10, 1000) . PHP_EOL;
                    }
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نام: " . PHP_EOL;
                    $msg .= $data->name . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس: " . PHP_EOL;
                    $msg .= $data->phone . PHP_EOL;
                    $msg .= " ⭐ " . "رشته ورزشی: " . implode(', ', collect(json_decode($data->times))->map(function ($el) {
                            return Sport::firstOrNew(['id' => $el->id])->name;
                        })->toArray()) . PHP_EOL;
                    $msg .= " 🚩 " . "استان: " . Province::firstOrNew(['id' => $data->province_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "شهر: " . County::firstOrNew(['id' => $data->county_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "آدرس: " . $data->address . PHP_EOL;
                    $msg .= " 📱 " . "توضیحات: " . $data->description . PHP_EOL;

                    break;

                case 'shop_created':
                    $msg .= " 🟣 " . "یک فروشگاه ساخته شد" . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $docs = $data->docs;
                    foreach ($docs as $doc) {
                        $msg .= url('') . '/storage/' . $doc->type_id . '/' . $doc['id'] . '.' . ($doc['type_id'] == Helper::$docsMap['video'] ? 'mp4' : 'jpg') . '?r=' . random_int(10, 1000) . PHP_EOL;
                    }
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نام: " . PHP_EOL;
                    $msg .= $data->name . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس: " . PHP_EOL;
                    $msg .= $data->phone . PHP_EOL;
                    $msg .= " 🚩 " . "استان: " . Province::firstOrNew(['id' => $data->province_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "شهر: " . County::firstOrNew(['id' => $data->county_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "آدرس: " . $data->address . PHP_EOL;
                    $msg .= " 📃 " . "توضیحات: " . $data->description . PHP_EOL;

                    break;

                case 'product_created':
                    $shop = \App\Models\Shop::firstOrNew(['id' => $data->shop_id]);
                    $msg .= " ⚫️ " . "یک محصول ساخته شد" . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $docs = $data->docs;
                    foreach ($docs as $doc) {
                        $msg .= url('') . '/storage/' . $doc->type_id . '/' . $doc['id'] . '.' . ($doc['type_id'] == Helper::$docsMap['video'] ? 'mp4' : 'jpg') . '?r=' . random_int(10, 1000) . PHP_EOL;
                    }
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نام: " . PHP_EOL;
                    $msg .= $data->name . PHP_EOL;
                    $msg .= " 📈 " . "قیمت اصلی: " . PHP_EOL;
                    $msg .= $data->price . PHP_EOL;
                    $msg .= " 📉 " . "قیمت با تخفیف: " . PHP_EOL;
                    $msg .= $data->discount_price . PHP_EOL;
                    $msg .= " 📊 " . "تعداد: " . PHP_EOL;
                    $msg .= $data->count . PHP_EOL;
                    $msg .= " 🚩 " . "دسته بندی: " . Sport::firstOrNew(['id' => $data->group_id])->name . PHP_EOL;
                    $msg .= " 🛒 " . "فروشگاه: " . PHP_EOL;
                    $msg .= $shop->name . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس: " . PHP_EOL;
                    $msg .= $shop->phone . PHP_EOL;
                    $msg .= " 📃 " . "توضیحات: " . $data->description . PHP_EOL;

                    break;

                case 'blog_created':
                    $user = \App\Models\User::firstOrNew(['id' => $data->user_id]);
                    $msg .= " 🟤 " . "یک خبر اضافه شد" . PHP_EOL;

                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نویسنده: " . PHP_EOL;
                    $msg .= ($user->name ? "$user->name $user->family" : "$user->username") . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس: " . PHP_EOL;
                    $msg .= $user->phone . PHP_EOL;
                    $msg .= " 📌 " . url('blog') . "/$data->id/" . str_replace(' ', '-', $data->title) . PHP_EOL;

                    break;

                case 'payment':
                    $user = \App\Models\User::firstOrNew(['id' => $data->user_id]);
                    $msg .= " ✔️ " . "یک پرداخت انجام شد" . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $msg .= " 🆔 " . "شماره سفارش: " . PHP_EOL . $data->order_id . PHP_EOL;
                    $msg .= " 💸 " . "مبلغ(ت): " . PHP_EOL . $data->amount . PHP_EOL;
                    $msg .= " 👤 " . "کاربر: " . PHP_EOL;
                    $msg .= ($user->name ? "$user->name $user->family" : "$user->username") . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس: " . PHP_EOL;
                    $msg .= $user->phone . PHP_EOL;
                    $msg .= " 🧾 " . "پیگیری شاپرک: " . PHP_EOL;
                    $msg .= $data->Shaparak_Ref_Id . PHP_EOL;
                    $msg .= " 📦 " . "محصول: " . PHP_EOL;
                    $msg .= $data->pay_for . PHP_EOL;

                    break;
                case 'user_edited':
                    $msg .= " 🟧 " . ($admin ? "ادمین *$admin* یک کاربر را ویرایش کرد" : "یک کاربر ویرایش شد") . PHP_EOL;
                    $msg .= " 👤 " . "نام: " . PHP_EOL;
                    $msg .= $data->fullname . PHP_EOL;
                    $msg .= " 📧 " . "ایمیل: " . PHP_EOL;
                    $msg .= $data->email . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس" . PHP_EOL;
                    $msg .= $data->phone . PHP_EOL;
                    $msg .= " 💰 " . "کیف پول" . PHP_EOL;
                    $msg .= $data->wallet . PHP_EOL;
                    $msg .= " 💳 " . "شماره کارت" . PHP_EOL;
                    $msg .= $data->card . PHP_EOL;
                    $msg .= " 🚧 " . "دسترسی" . PHP_EOL;
                    $msg .= $data->access . PHP_EOL;
                    break;

                case 'player_edited':
                    $msg .= " 🟧 " . ($admin ? "ادمین *$admin* $attribute یک بازیکن را ویرایش کرد" : " $attribute یک بازیکن ویرایش شد") . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $docs = $data->docs;
                    foreach ($docs as $doc) {
                        $msg .= url('') . '/storage/' . $doc->type_id . '/' . $doc['id'] . '.' . ($doc['type_id'] == Helper::$docsMap['video'] ? 'mp4' : 'jpg') . '?r=' . random_int(10, 1000) . PHP_EOL;
                    }
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نام: " . PHP_EOL;
                    $msg .= $data->name . ' ' . $data->family . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس: " . PHP_EOL;
                    $msg .= $data->phone . PHP_EOL;
                    $msg .= " 🧬 " . "جنسیت: " . ($data->is_man ? 'مرد' : 'زن') . PHP_EOL;
                    $msg .= " 📅 " . "تاریخ تولد: " . Jalalian::fromDateTime($data->born_at)->format('%Y/%m/%d') . PHP_EOL;
                    $msg .= " 📏 " . "قد: " . $data->height . PHP_EOL;
                    $msg .= " ⚓ " . "وزن: " . $data->weight . PHP_EOL;
                    $msg .= " ⭐ " . "رشته ورزشی: " . Sport::firstOrNew(['id' => $data->sport_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "استان: " . Province::firstOrNew(['id' => $data->province_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "شهر: " . County::firstOrNew(['id' => $data->county_id])->name . PHP_EOL;
                    $msg .= " 📃 " . "توضیحات: " . $data->description . PHP_EOL;

                    break;

                case 'coach_edited':
                    $msg .= " 🟨 " . ($admin ? "ادمین *$admin* $attribute یک مربی را ویرایش کرد" : " $attribute یک مربی ویرایش شد") . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $docs = $data->docs;
                    foreach ($docs as $doc) {
                        $msg .= url('') . '/storage/' . $doc->type_id . '/' . $doc['id'] . '.' . ($doc['type_id'] == Helper::$docsMap['video'] ? 'mp4' : 'jpg') . '?r=' . random_int(10, 1000) . PHP_EOL;
                    }
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نام: " . PHP_EOL;
                    $msg .= $data->name . ' ' . $data->family . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس: " . PHP_EOL;
                    $msg .= $data->phone . PHP_EOL;
                    $msg .= " 🧬 " . "جنسیت: " . ($data->is_man ? 'مرد' : 'زن') . PHP_EOL;
                    $msg .= " 📅 " . "تاریخ تولد: " . Jalalian::fromDateTime($data->born_at)->format('%Y/%m/%d') . PHP_EOL;
                    $msg .= " ⭐ " . "رشته ورزشی: " . Sport::firstOrNew(['id' => $data->sport_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "استان: " . Province::firstOrNew(['id' => $data->province_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "شهر: " . County::firstOrNew(['id' => $data->county_id])->name . PHP_EOL;
                    $msg .= " 📃 " . "توضیحات: " . $data->description . PHP_EOL;

                    break;

                case 'club_edited':
                    $msg .= " 🟩 " . ($admin ? "ادمین *$admin* $attribute یک باشگاه را ویرایش کرد" : " $attribute یک باشگاه ویرایش شد") . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $docs = $data->docs;
                    foreach ($docs as $doc) {
                        $msg .= url('') . '/storage/' . $doc->type_id . '/' . $doc['id'] . '.' . ($doc['type_id'] == Helper::$docsMap['video'] ? 'mp4' : 'jpg') . '?r=' . random_int(10, 1000) . PHP_EOL;
                    }
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نام: " . PHP_EOL;
                    $msg .= $data->name . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس: " . PHP_EOL;
                    $msg .= $data->phone . PHP_EOL;
                    $msg .= " ⭐ " . "رشته ورزشی: " . implode(', ', collect(json_decode($data->times))->map(function ($el) {
                            return Sport::firstOrNew(['id' => $el->id])->name;
                        })->toArray()) . PHP_EOL;
                    $msg .= " 🚩 " . "استان: " . Province::firstOrNew(['id' => $data->province_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "شهر: " . County::firstOrNew(['id' => $data->county_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "آدرس: " . $data->address . PHP_EOL;
                    $msg .= " 📃 " . "توضیحات: " . $data->description . PHP_EOL;

                    break;

                case 'shop_edited':
                    $msg .= " 🟦 " . ($admin ? "ادمین *$admin* $attribute یک فروشگاه را ویرایش کرد" : " $attribute یک فروشگاه ویرایش شد") . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $docs = $data->docs;
                    foreach ($docs as $doc) {
                        $msg .= url('') . '/storage/' . $doc->type_id . '/' . $doc['id'] . '.' . ($doc['type_id'] == Helper::$docsMap['video'] ? 'mp4' : 'jpg') . '?r=' . random_int(10, 1000) . PHP_EOL;
                    }
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نام: " . PHP_EOL;
                    $msg .= $data->name . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس: " . PHP_EOL;
                    $msg .= $data->phone . PHP_EOL;
                    $msg .= " 🚩 " . "استان: " . Province::firstOrNew(['id' => $data->province_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "شهر: " . County::firstOrNew(['id' => $data->county_id])->name . PHP_EOL;
                    $msg .= " 🚩 " . "آدرس: " . $data->address . PHP_EOL;
                    $msg .= " 📃 " . "توضیحات: " . $data->description . PHP_EOL;

                    break;

                case 'product_edited':
                    $shop = \App\Models\Shop::firstOrNew(['id' => $data->shop_id]);
                    $msg .= " 🟪 " . ($admin ? "ادمین *$admin* $attribute یک محصول را ویرایش کرد" : " $attribute یک محصول ویرایش شد") . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $docs = $data->docs;
                    foreach ($docs as $doc) {
                        $msg .= url('') . '/storage/' . $doc->type_id . '/' . $doc['id'] . '.' . ($doc['type_id'] == Helper::$docsMap['video'] ? 'mp4' : 'jpg') . '?r=' . random_int(10, 1000) . PHP_EOL;
                    }
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نام: " . PHP_EOL;
                    $msg .= $data->name . PHP_EOL;
                    $msg .= " 📈 " . "قیمت اصلی: " . PHP_EOL;
                    $msg .= $data->price . PHP_EOL;
                    $msg .= " 📉 " . "قیمت با تخفیف: " . PHP_EOL;
                    $msg .= $data->discount_price . PHP_EOL;
                    $msg .= " 📊 " . "تعداد: " . PHP_EOL;
                    $msg .= $data->count . PHP_EOL;
                    $msg .= " 🚩 " . "دسته بندی: " . Sport::firstOrNew(['id' => $data->group_id])->name . PHP_EOL;
                    $msg .= " 🛒 " . "فروشگاه: " . PHP_EOL;
                    $msg .= $shop->name . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس: " . PHP_EOL;
                    $msg .= $shop->phone . PHP_EOL;
                    $msg .= " 📃 " . "توضیحات: " . $data->description . PHP_EOL;

                    break;
                case 'product_deleted':
                    $shop = \App\Models\Shop::firstOrNew(['id' => $data->shop_id]);
                    $msg .= " 📛 " . ($admin ? "ادمین *$admin* یک محصول را حذف کرد" : "یک محصول حذف شد") . PHP_EOL;
                    $msg .= "\xD8\x9C" . "➖➖➖➖➖➖➖➖➖➖➖" . PHP_EOL;
                    $msg .= " 🆔 " . "شناسه: " . $data->id . PHP_EOL;
                    $msg .= " 👤 " . "نام: " . PHP_EOL;
                    $msg .= $data->name . PHP_EOL;
                    $msg .= " 📈 " . "قیمت اصلی: " . PHP_EOL;
                    $msg .= $data->price . PHP_EOL;
                    $msg .= " 📉 " . "قیمت با تخفیف: " . PHP_EOL;
                    $msg .= $data->discount_price . PHP_EOL;
                    $msg .= " 📊 " . "تعداد: " . PHP_EOL;
                    $msg .= $data->count . PHP_EOL;
                    $msg .= " 🚩 " . "دسته بندی: " . Sport::firstOrNew(['id' => $data->group_id])->name . PHP_EOL;
                    $msg .= " 🛒 " . "فروشگاه: " . PHP_EOL;
                    $msg .= $shop->name . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس: " . PHP_EOL;
                    $msg .= $shop->phone . PHP_EOL;
                    $msg .= " 📃 " . "توضیحات: " . $data->description . PHP_EOL;

                    break;
                case 'message_created':
                    if ($data->type == 'order')
                        $msg .= " 🟩🟩🟩 " . "یک سفارش ثبت شد" . PHP_EOL;
                    elseif ($data->type == 'referral')
                        $msg .= " 🟦🟦🟦 " . "یک درخواست بازاریابی ثبت شد" . PHP_EOL;
                    else
                        $msg .= " 🟪🟪🟪 " . "یک پیام ثبت شد" . PHP_EOL;
                    $msg .= " 🆔 " . "شناسه پیام: " . optional($data)->id . PHP_EOL;
                    $msg .= " 👤 " . "نام " . PHP_EOL;
                    $msg .= $data->fullname . PHP_EOL;
                    $msg .= " 📱 " . "شماره تماس" . PHP_EOL;
                    $msg .= $data->phone . PHP_EOL;
                    $msg .= " 📃 " . "پیام" . PHP_EOL;
                    $msg .= $data->description . PHP_EOL;

                    break;
                case 'error':
                    $msg = ' 📛 ' . ' خطای سیستم ' . PHP_EOL . $data;
                    break;
                default :
                    $msg = $data;
            }
            if ($to) {
//                self::sendMessage($to, $msg, null);
                Bale::sendMessage($to, $msg, null);
                Eitaa::logAdmins($msg, $type,);
            } else {
                self::logAdmins($msg, null);
//                Bale::logAdmins($msg, null);
//                Eitaa::logAdmins($msg, $type,);
            }

        } catch (\Exception $e) {
            try {
                Bale::logAdmins($e->getMessage(), $type);
                Eitaa::logAdmins($e->getMessage(), $type,);
//            return self::sendMessage(Variable::LOGS[0], $e->getMessage(), null);
            } catch (\Exception $e) {
            };
        }
    }
}
