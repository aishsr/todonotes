<?php

namespace Database\Factories;

use App\Models\ToDoNote;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Scrawlr\Identifiers\Uuid\Rfc4122\V4\UuidV4;

class ToDoNoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ToDoNote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $rows = env('APP_DATA_ROWS', 50);
        $users = User::all()->pluck('uuid');

        foreach(range(1, $rows) as $index){
            $todonote = ToDoNote::create([
                'uuid' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                'content' => $this->faker->text(200),
                'userid' => $this->faker->randomElement($users),
            ]);
        }
    }
}
