<?php

declare(strict_types = 1);

use App\Brain\Chat\Tasks\AttachChannelToTheUserTask;
use App\Models\Channel;
use App\Models\User;
use App\Models\Workspace;

use function Pest\Laravel\assertDatabaseHas;

it('should work', function (): void {
    $user      = User::factory()->create();
    $workspace = Workspace::factory()->create(['owner_id' => $user->id]);
    $channel   = Channel::factory()->create(['workspace_id' => $workspace->id]);

    AttachChannelToTheUserTask::dispatch([
        'channel_id' => $channel->id,
        'user'       => $user,
    ]);

    assertDatabaseHas('channel_user', [
        'channel_id' => $channel->id,
        'user_id'    => $user->id,
    ]);
});
