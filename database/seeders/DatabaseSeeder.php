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
        // Create 30 threads and attach a random
        // number of replies to each of them
        Thread::factory(30)->create();
    }
}
