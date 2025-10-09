<?php

declare(strict_types = 1);

namespace App\Brain\User\Processes;

use App\Brain\User\Tasks\CreateUserTask;
use App\Brain\User\Tasks\SendWelcomeEmailTask;
use App\Brain\User\Tasks\ValidateCreateUserTask;
use Brain\Process;

class CreateUserProcess extends Process
{
    protected array $tasks = [
        ValidateCreateUserTask::class,
        CreateUserTask::class,
        SendWelcomeEmailTask::class,
    ];
}
