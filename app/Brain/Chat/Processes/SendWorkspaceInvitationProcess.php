<?php

declare(strict_types = 1);

namespace App\Brain\Chat\Processes;

use App\Brain\Chat\Tasks\CheckIfUserCanISendInviteToWorkspaceTask;
use App\Brain\Chat\Tasks\CreateWorkspaceInviteTask;
use App\Brain\Chat\Tasks\SendEmailWithTheWorkspaceInvitationTask;
use Brain\Process;

class SendWorkspaceInvitationProcess extends Process
{
    protected array $tasks = [
        CheckIfUserCanISendInviteToWorkspaceTask::class,
        CreateWorkspaceInviteTask::class,
        SendEmailWithTheWorkspaceInvitationTask::class,
    ];
}
