<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\ToDoNote;
use App\Models\User;
use Faker\Factory as Faker;
use Symfony\Component\Console\Output\ConsoleOutput;


class ToDoNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ToDoNote::factory()->create();
    }
}
