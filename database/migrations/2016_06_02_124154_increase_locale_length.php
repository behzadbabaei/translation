<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;

use function config;

class IncreaseLocaleLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('translator.connection'))->table('translator_languages', function ($table) {
            $table->string('locale', 10)->change();
        });
        Schema::connection(config('translator.connection'))->table('translator_translations', function ($table) {
            $table->string('locale', 10)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
