<?php

declare(strict_types = 1);

use App\Brain\Chat\Tasks\CheckIfUserIsAlreadyInTheChannelTask;
use App\Models\Channel;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Validation\ValidationException;

beforeEach(function (): void {
    $this->user      = User::factory()->create();
    $this->workspace = Workspace::factory()->create(['owner_id' => $this->user->id]);
    $this->channel   = Channel::factory()->create(['workspace_id' => $this->workspace->id]);
});

it('should not throw an error if the user is not in the channel', function (): void {
    expect(fn () => CheckIfUserIsAlreadyInTheChannelTask::dispatch([
        'channel_id' => $this->channel->id,
        'user_id'    => $this->user->id,
    ]))->not->toThrow(ValidationException::class);
});

it('should return user and workspace_id in the payload', function (): void {
    $payload = CheckIfUserIsAlreadyInTheChannelTask::dispatchSync([
        'channel_id' => $this->channel->id,
        'user_id'    => $this->user->id,
    ]);

    expect($payload->user->id)->toBe($this->user->id);
    expect($payload->workspace_id)->toBe($this->workspace->id);
});

it('should throw an error if the user is already in the channel', function (): void {
    $this->channel->users()->attach($this->user->id);

    expect(fn () => CheckIfUserIsAlreadyInTheChannelTask::dispatch([
        'channel_id' => $this->channel->id,
        'user_id'    => $this->user->id,
    ]))->toThrow(ValidationException::class);
});

// ----------------------------------------------------------------------
// Validations
//
// 'channel_id' => ['required', 'integer', 'exists:channels,id'],
// 'user_id'    => ['required', 'integer', 'exists:users,id'],
//

test('channel_id is required', function (): void {
    expect(fn () => CheckIfUserIsAlreadyInTheChannelTask::dispatch([
    ]))->toThrow(
        ValidationException::class,
        __('validation.required', ['attribute' => 'channel id'])
    );
});

test('channel_id must be an integer', function (): void {
    expect(fn () => CheckIfUserIsAlreadyInTheChannelTask::dispatch([
        'channel_id' => 'invalid',
    ]))->toThrow(
        ValidationException::class,
        __('validation.integer', ['attribute' => 'channel id'])
    );
});

test('channel_id must exist in channels table', function (): void {
    expect(fn () => CheckIfUserIsAlreadyInTheChannelTask::dispatch([
        'channel_id' => 9999,
    ]))->toThrow(
        ValidationException::class,
        __('validation.exists', ['attribute' => 'channel id'])
    );
});

test('user_id is required', function (): void {
    expect(fn () => CheckIfUserIsAlreadyInTheChannelTask::dispatch([
        'channel_id' => $this->channel->id,
    ]))->toThrow(
        ValidationException::class,
        __('validation.required', ['attribute' => 'user id'])
    );
});

test('user_id must be an integer', function (): void {
    expect(fn () => CheckIfUserIsAlreadyInTheChannelTask::dispatch([
        'channel_id' => $this->channel->id,
        'user_id'    => 'invalid',
    ]))->toThrow(
        ValidationException::class,
        __('validation.integer', ['attribute' => 'user id'])
    );
});

test('user_id must exist in users table', function (): void {
    expect(fn () => CheckIfUserIsAlreadyInTheChannelTask::dispatch([
        'channel_id' => $this->channel->id,
        'user_id'    => 9999,
    ]))->toThrow(
        ValidationException::class,
        __('validation.exists', ['attribute' => 'user id'])
    );
});
