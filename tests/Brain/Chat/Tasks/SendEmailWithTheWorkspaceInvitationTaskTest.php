<?php

declare(strict_types = 1);

use App\Brain\Chat\Tasks\SendEmailWithTheWorkspaceInvitationTask;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInvite;
use Brain\Exceptions\InvalidPayload;
use Illuminate\Support\Facades\Notification;

beforeEach(function (): void {
    $this->owner       = User::factory()->create();
    $this->workspace   = Workspace::factory()->create(['owner_id' => $this->owner->id]);
    $this->invitedUser = User::factory()->create(['username' => 'janedoe']);
    $this->invite      = WorkspaceInvite::query()->create([
        'workspace_id' => $this->workspace->id,
        'invited_by'   => $this->owner->id,
        'username'     => 'janedoe',
    ]);
});

it('should send the notification for the invited user', function (): void {
    Notification::fake();

    SendEmailWithTheWorkspaceInvitationTask::dispatchSync([
        'invite' => $this->invite,
    ]);

    Notification::assertSentTo(
        [$this->invitedUser],
        App\Notifications\WorkspaceInvitationNotification::class
    );
});

it('should fail if the invited user does not exist', function (): void {
    Notification::fake();

    $invite = WorkspaceInvite::query()->create([
        'workspace_id' => $this->workspace->id,
        'invited_by'   => $this->owner->id,
        'username'     => 'nonexistentuser',
    ]);

    expect(fn () => SendEmailWithTheWorkspaceInvitationTask::dispatchSync([
        'invite' => $invite,
    ]))->toThrow(
        Illuminate\Database\Eloquent\ModelNotFoundException::class
    );

    Notification::assertNothingSent();
});

it('should fail if the invite is not provided', function (): void {
    expect(fn () => SendEmailWithTheWorkspaceInvitationTask::dispatchSync([]))
        ->toThrow(
            InvalidPayload::class
        );
});

it('should implement ShouldQueueAfterCommit interface', function (): void {
    $task = new SendEmailWithTheWorkspaceInvitationTask([
        'invite' => $this->invite,
    ]);

    expect($task)->toBeInstanceOf(Illuminate\Contracts\Queue\ShouldQueueAfterCommit::class);
});
