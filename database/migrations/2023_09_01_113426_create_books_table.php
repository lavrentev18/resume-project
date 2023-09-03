<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string     ('name');
            $table->string     ('author');
            $table->string     ('slug')->unique();
            //$table->tinyInteger('status');
            $table->string     ('genre');
            $table->string     ('publisher');
            $table->longText   ('description');
            $table->integer    ('user_id')->nullable();
            $table->dateTime   ('reserved_at')->nullable();
            $table->dateTime   ('take_at')->nullable();
            $table->timestamps ();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
