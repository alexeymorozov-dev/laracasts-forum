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
        // Create 50 threads and attach each of them
        // to a random user and a channel
        Thread::factory(50)
            ->create([
                'user_id' => rand(1, 10),
                'channel_id' => rand(1, 5)
            ])->each(function ($thread) {
                // create 5 replies for each thread
                // and attach each of them to a random user
                Reply::factory(5)
                    ->create([
                        'thread_id' => $thread->id,
                        'user_id' => rand(1, 10)
                    ]);
            });


    }
}
