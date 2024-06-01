<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nette\Utils\Type;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';
    public $timestamps = true;
    protected $fillable = [
        'subject',
        'agency_id',
        'status',
        'from_id',
        'from_type',
        'to_id',
        'to_type',
        'from_notification',
        'to_notification',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'from_notification' => 'boolean',
        'to_notification' => 'boolean',
    ];

    public function chats()
    {
        return $this->hasMany(TicketChat::class, 'ticket_id')->orderBy('id', 'DESC');
    }

    public function getUpdatedAtAttributes($value)
    {
        if (!$value) return $value;
        return \Morilog\Jalali\CalendarUtils::strftime('Y/m/d | H:i', strtotime($value));
    }

    public function isResponse($me)
    {
        return ($me instanceof Admin) &&
            (
                ($this->from_type == 'user' || $this->to_type == 'user')
                ||
                ($this->from_type == 'admin' && $this->to_type == 'admin' && $me->agency_id == 1)
            );
    }

    function setNotify($user, $type = null)
    {
        if ($user instanceof Admin) {
            if ($this->from_id == $user->id && $this->from_type == 'admin') {
                if ($type == 'add-chat')
                    $this->to_notification = true;
                if ($type == 'seen')
                    $this->from_notification = false;
            }
            if ($this->to_id == $user->id && $this->to_type == 'admin') {
                if ($type == 'add-chat')
                    $this->from_notification = true;
                if ($type == 'seen')
                    $this->to_notification = false;

            }
        } else {
            if ($this->from_id == $user->id && $this->from_type == 'user') {
                if ($type == 'add-chat')
                    $this->to_notification = true;
                if ($type == 'seen')
                    $this->from_notification = false;

            }
            if ($this->to_id == $user->id && $this->to_type == 'user') {
                if ($type == 'add-chat')
                    $this->from_notification = true;
                if ($type == 'seen')
                    $this->to_notification = false;

            }
        }
        $this->save();
    }
}
