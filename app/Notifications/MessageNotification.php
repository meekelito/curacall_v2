<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Message;
use App\Notifications\CustomDbChannel;

class MessageNotification extends Notification
{
    use Queueable;
    public $message;
    public $isgroupchat;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Message $message,$isgroupchat = false)
    {
        $this->message = $message;
        $this->isgroupchat = $isgroupchat;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ChatDbChannel::class,'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {
        return [
            'from_id'   => $this->message->user['id'],
            'from_name'   => $this->message->user['fname'] . ' ' . $this->message->user['lname'],
            'from_image' => $this->isgroupchat ? 'groupchat.png' : $this->message->user['prof_img'],
            'message_id'    => $this->message['id'],
            'room_id'    => $this->message['room_id'],
            'message' => $this->message['message'],
            'action_url'    => route('chat.room',[$this->message['room_id']])
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'prof_img'  => asset('storage/uploads/users').'/'. $this->message->user['prof_img'],
            'data' => [
                'from_id'   => $this->message->user['id'],
                'from_name'   => $this->message->user['fname'] . ' ' . $this->message->user['lname'],
                'from_image' => $this->message->user['prof_img'],
                'message_id'    => $this->message['id'],
                'room_id'    => $this->message['room_id'],
                'message' => $this->message['message'],
                'action_url'    => route('chat.room',[$this->message['room_id']])
            ],
            "created_at"    =>  $this->message['created_at']->diffForHumans(),
            "is_read"       => 0   
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
