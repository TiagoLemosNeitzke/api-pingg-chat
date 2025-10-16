<?php

declare(strict_types = 1);

namespace App\Brain\Chat\Tasks;

use App\Models\User;
use App\Models\WorkspaceInvite;
use App\Notifications\WorkspaceInvitationNotification;
use Brain\Task;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Support\Facades\Notification;

/**
 * Task SendEmailWithTheWorkspaceInvitationTask
 *
 * @property-read WorkspaceInvite $invite
 */
class SendEmailWithTheWorkspaceInvitationTask extends Task implements ShouldQueueAfterCommit
{
    public function handle(): self
    {
        $invited = User::query()
            ->where('username', $this->invite->username)
            ->firstOrFail();

        Notification::send(
            $invited,
            new WorkspaceInvitationNotification($this->invite)
        );

        return $this;
    }
}
