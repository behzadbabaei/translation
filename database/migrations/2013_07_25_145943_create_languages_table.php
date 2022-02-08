<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;

use function config;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('translator.connection'))->create('translator_languages', function ($table) {
            $table->increments('id');
            $table->string('locale', 6)->unique();
            $table->string('name', 60)->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('translator_languages');
    }
}
