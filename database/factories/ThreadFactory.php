<?php

namespace Database\Factories;

use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThreadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Thread::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph(rand(4, 10)),
            'user_id' => rand(1, 10),
            'channel_id' => rand(1, 5),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Thread $thread) {
            $count = rand(0, 10);
            $thread->setAttribute('replies_count', $count);
            Reply::factory($count)->create(['thread_id' => $thread->id]);
            $thread->save();
        });
    }
}
