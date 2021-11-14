<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTodoNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // To Do Notes table
        Schema::create('to_do_notes', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('userid');
            $table->string('content')->nullable();      //change to text()
            $table->timestamp('completion_time', 0)->nullable();
            $table->timestamps();

            $table->foreign('userid')->references('uuid')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('to_do_notes');
    }
}
