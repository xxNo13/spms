<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AssignmentNotification extends Notification
{
    use Queueable;
    private $assignment;
    private $status;
    private $user;
    private $head;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($assignment, $status = null)
    {
        $this->assignment = $assignment;
        $this->status = $status;
        $this->user = User::where('id', $assignment->user_id)->first();
        $this->head = User::where('id', $assignment->head_id)->first();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }


    public function toDatabase($notifiable)
    {
        return [
            'ttma_id' => $this->assignment->id,
            'subject' => $this->assignment->subject,
            'output' => $this->assignment->output,
            'remarks' => $this->assignment->remarks,
            'status' => $this->status,
            'user' => $this->user->name,
            'head' => $this->head->name,
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
