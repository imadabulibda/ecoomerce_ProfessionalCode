<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorCreated extends Notification
{
    use Queueable;


    public $vendor;

    /**
     * Create a new notification instance.
     *
     * @param Vendor $vendor
     */
    public function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = sprintf('%s: لقد تم انشاء حسابكم في سوق العمدة %s!', config('app.name'), 'Emad');
        $greeting = sprintf('مرحبا %s', $notifiable->name);

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->salutation('سوق العمدة')
            ->line('لقد تم إنشاء حسابكم في سوق العمدة، بإمكانكم الآن تسجيل الدخول للوحة التحكم الخاصة بكم.')
            ->action('لوحة التحكم', url('/'))
            ->line('شكراً لك لإنك أصبحت أحد أفراد عائلة سوق العمدة');
    }

    /**
     * Get the array representation of the notification. if we used via database.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
