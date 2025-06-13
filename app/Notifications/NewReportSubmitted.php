<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\User;

class NewReportSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public User $reporter;
    public array $analysisData;

    /**
     * Create a new notification instance.
     *
     * @param User $reporter The user who submitted the report.
     * @param array $analysisData The AI-generated data (summary, suggestions).
     */
    public function __construct(User $reporter, array $analysisData)
    {
        $this->reporter = $reporter;
        $this->analysisData = $analysisData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * We only need to store it in the database for our UI to pick it up.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * This is the data that will be stored in the 'data' column
     * of the notifications table as a JSON object.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'reporter_id' => $this->reporter->id,
            'reporter_name' => $this->reporter->name,
            'summary' => $this->analysisData['summary'] ?? ['No summary available.'],
            'suggestions' => $this->analysisData['suggestions'] ?? [],
        ];
    }
}