<?php declare(strict_types = 1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Scrawlr\Identifiers\Uuid\Rfc4122\V4\UuidV4;

/**
 * @extends() \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $uuid = new UuidV4();

        return [
            'uri' => $uuid->generateUuid(),
            'title' => $this->faker->text(),
            'content' => $this->faker->paragraph(),
            'type' => 'post',
        ];
    }
}
