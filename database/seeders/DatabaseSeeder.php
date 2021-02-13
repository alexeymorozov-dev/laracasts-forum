<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create 5 channels
        Channel::factory(5)->create();
        // Create 10 users
        User::factory(10)->create();
        // Create 50 threads
        Thread::factory(50)->create();
        // Create 100 replies
        Reply::factory(100)->create();
    }
}
