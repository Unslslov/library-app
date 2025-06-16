<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookAvailableNotification extends Notification
{
    use Queueable;

    protected $book;

    public function __construct($book)
    {
        $this->book = $book;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Книга "' . $this->book->title . '" снова доступна!')
            ->greeting('Здравствуйте!')
            ->line('Книга, которую вы отслеживали, теперь доступна для бронирования')
            ->action('Посмотреть книгу', url('api/clients/books/' . $this->book->id))
            ->line('Скорее забронируйте её!')
            ->salutation('С уважением, Администрация библиотеки');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'book_id' => $this->book->id,
            'title' => $this->book->ttile,
            'message' => 'Книга "' . $this->book->title . '" снова доступна!'
        ];
    }
}
