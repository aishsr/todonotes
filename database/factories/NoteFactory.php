<?php declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class NoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userId = DB::table('users')->get()->random()->uuid;

        return [
            'uuid' => Uuid::uuid4()->toString(),
            'content' => $this->faker->paragraph(1),
            'userid' => $userId,
        ];
    }
}
