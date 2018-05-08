<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Report;
use App\Incident;

class NewReport extends Notification implements ShouldQueue
{
    use Queueable;

    protected $report;
    protected $incident;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Report $report)
    {
      $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
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
           'report_id' => $this->report->id
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
          'id' => $this->report->id,
          'read_at' => null,
          'data' => [
              'report_id' => $this->report->id,
          ],
        ];
    }
}
