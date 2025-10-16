<?php

declare(strict_types = 1);

use App\Brain\Chat\Processes\CreateUserProcess;
use App\Brain\Chat\Tasks\CreateUserTask;
use App\Brain\Chat\Tasks\SendWelcomeEmailTask;

test('check list of tasks', function (): void {
    $process = new CreateUserProcess();

    expect($process->getTasks())
        ->toBe([
            CreateUserTask::class,
            SendWelcomeEmailTask::class,
        ]);
});
