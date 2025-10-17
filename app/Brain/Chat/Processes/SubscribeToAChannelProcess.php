<?php

declare(strict_types = 1);

namespace App\Brain\Chat\Processes;

use App\Brain\Chat\Tasks\AttachChannelToTheUserTask;
use App\Brain\Chat\Tasks\CheckIfUserHasAccessToTheWorkspaceTask;
use App\Brain\Chat\Tasks\CheckIfUserIsAlreadyInTheChannelTask;
use Brain\Process;

class SubscribeToAChannelProcess extends Process
{
    protected array $tasks = [
        CheckIfUserIsAlreadyInTheChannelTask::class,
        CheckIfUserHasAccessToTheWorkspaceTask::class,
        AttachChannelToTheUserTask::class,
    ];
}
