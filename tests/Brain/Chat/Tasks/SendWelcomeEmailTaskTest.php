<?php

declare(strict_types = 1);

use App\Models\User;
use Illuminate\Support\Facades\Notification;

it('should send WelcomeNotification to the given user', function (): void {
    Notification::fake();
    $user = User::factory()->create();

    App\Brain\Chat\Tasks\SendWelcomeEmailTask::dispatchSync([
        'user' => $user,
    ]);

    Notification::assertSentTo($user, App\Notifications\WelcomeEmailNotification::class);
});
