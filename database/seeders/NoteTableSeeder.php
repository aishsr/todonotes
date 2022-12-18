<?php declare(strict_types = 1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Note;

class NoteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Note::factory(50)->create();
    }
}
