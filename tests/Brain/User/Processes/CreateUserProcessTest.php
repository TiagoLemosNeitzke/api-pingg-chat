<?php

declare(strict_types = 1);

use App\Brain\User\Processes\CreateUserProcess;
use App\Brain\User\Tasks\CreateUserTask;
use App\Brain\User\Tasks\SendWelcomeEmailTask;
use App\Brain\User\Tasks\ValidateCreateUserTask;

test('check list of tasks', function (): void {
    $process = new CreateUserProcess();

    expect($process->getTasks())
        ->toBe([
            ValidateCreateUserTask::class,
            CreateUserTask::class,
            SendWelcomeEmailTask::class,
        ]);
});
