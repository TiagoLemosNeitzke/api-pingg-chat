<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        Workspace::all()
            ->each(fn (Workspace $workspace) => Channel::factory()->count(3)->create(['workspace_id' => $workspace->id]));
    }
}
