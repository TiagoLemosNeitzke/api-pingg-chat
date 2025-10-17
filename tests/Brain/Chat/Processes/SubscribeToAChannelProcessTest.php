<?php

declare(strict_types = 1);

use App\Brain\Chat\Processes\SubscribeToAChannelProcess;
use App\Brain\Chat\Tasks\AttachChannelToTheUserTask;
use App\Brain\Chat\Tasks\CheckIfUserHasAccessToTheWorkspaceTask;
use App\Brain\Chat\Tasks\CheckIfUserIsAlreadyInTheChannelTask;
use App\Models\Channel;
use App\Models\User;
use App\Models\Workspace;

use function Pest\Laravel\assertDatabaseHas;

test('check list of tasks', function (): void {
    $process = new SubscribeToAChannelProcess();

    expect($process->getTasks())
        ->toBe([
            CheckIfUserIsAlreadyInTheChannelTask::class,
            CheckIfUserHasAccessToTheWorkspaceTask::class,
            AttachChannelToTheUserTask::class,
        ]);
});

it('should works', function (): void {
    $user      = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $workspace->users()->attach($user->id);
    $channel = Channel::factory()->create(['workspace_id' => $workspace->id]);

    SubscribeToAChannelProcess::dispatch([
        'user_id'    => $user->id,
        'channel_id' => $channel->id,
    ]);

    assertDatabaseHas('channel_user', [
        'user_id'    => $user->id,
        'channel_id' => $channel->id,
    ]);
});
