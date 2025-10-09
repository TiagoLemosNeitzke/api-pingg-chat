<?php

declare(strict_types = 1);

use App\Brain\User\Tasks\CreateUserTask;

use function Pest\Laravel\assertDatabaseHas;

it('should be able to create a new user', function (): void {
    CreateUserTask::dispatch([
        'name'     => 'Valid Name',
        'username' => 'validusername',
        'email'    => 'valid@email.com',
    ]);

    assertDatabaseHas('users', [
        'name'     => 'Valid Name',
        'username' => 'validusername',
        'email'    => 'valid@email.com',
    ]);
});

it('should return the created user', function (): void {
    $task = CreateUserTask::dispatchSync([
        'name'     => 'Valid Name',
        'username' => 'validusername',
        'email'    => 'valid@email.com',
    ]);

    expect($task->user)->toBeInstanceOf(App\Models\User::class)
        ->and($task->user->name)->toBe('Valid Name')
        ->and($task->user->username)->toBe('validusername')
        ->and($task->user->email)->toBe('valid@email.com');
});
