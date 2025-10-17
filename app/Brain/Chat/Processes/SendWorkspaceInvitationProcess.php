<?php

declare(strict_types = 1);

namespace App\Brain\Chat\Processes;

use App\Brain\Chat\Tasks\CheckIfUserCanISendInviteToWorkspaceTask;
use App\Brain\Chat\Tasks\CreateWorkspaceInviteTask;
use App\Brain\Chat\Tasks\SendEmailWithTheWorkspaceInvitationTask;
use Brain\Process;

/**
* Process SendWorkspaceInvitationProcess
*
* @property-read int $workspace_id
* @property-read int $invited_by
* @property-read string $username
*/
class SendWorkspaceInvitationProcess extends Process
{
    protected array $tasks = [
        CheckIfUserCanISendInviteToWorkspaceTask::class,
        CreateWorkspaceInviteTask::class,
        SendEmailWithTheWorkspaceInvitationTask::class,
    ];
}
