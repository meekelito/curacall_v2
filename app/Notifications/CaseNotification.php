<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class CaseNotification extends Notification
{
    use Queueable;
    public $fields;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($fields)
    {
        $this->fields = $fields;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [CustomDbChannel::class,'broadcast'];
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
            'from_id'   => $this->fields['from_id'],
            'from_name'   => $this->fields['from_name'],
            'from_image' =>  $this->fields['from_image'],
            'case_id'      =>   $this->fields['case_id'],
            'message' => $this->fields['message'],
            'action_url'    =>  $this->fields['action_url']
        ];
    }

    public function toBroadcast($notifiable)
    {
        return [
            'data' => [
                'from_id'   => $this->fields['from_id'],
                'from_name'   => $this->fields['from_name'],
                'from_image' =>  $this->fields['from_image'],
                'message' => $this->fields['message'],
                'action_url'    =>  $this->fields['action_url']
            ],
            "created_at"    =>  Carbon::now()->diffForHumans(),
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
