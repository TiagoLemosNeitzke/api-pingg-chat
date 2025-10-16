<?php

declare(strict_types = 1);

namespace App\Notifications;

use App\Models\WorkspaceInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkspaceInvitationNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected WorkspaceInvite $invite
    ) {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->line('You have a new workspace invitation!')
            ->line('Workspace: ' . $this->invite->workspace->name)
            ->line('Invited by: ' . $this->invite->invitedBy->username)
            ->line('`ssh pingg.me` to accept the invitation.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
