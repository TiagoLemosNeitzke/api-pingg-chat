<?php

declare(strict_types = 1);

namespace App\Brain\Chat\Processes;

use App\Brain\Chat\Tasks\CreateUserTask;
use App\Brain\Chat\Tasks\SendWelcomeEmailTask;
use Brain\Process;

class CreateUserProcess extends Process
{
    protected array $tasks = [
        CreateUserTask::class,
        SendWelcomeEmailTask::class,
    ];
}
