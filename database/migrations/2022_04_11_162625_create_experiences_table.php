<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    const TABLE_NAME = 'experiences';

    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->increments('id');
            $table->string('company')->nullable(false);
            $table->text('description')->nullable(false);
            $table->date('start')->nullable(false);
            $table->date('end')->nullable();
            $table->boolean('active');
            $table->integer('resume_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->integer('registered_by')->unsigned();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->foreign('resume_id')->references('id')->on('resumes');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('registered_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
}
