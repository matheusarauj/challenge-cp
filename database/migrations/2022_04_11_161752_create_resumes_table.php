<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResumesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    const TABLE_NAME = 'resumes';

    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name')->nullable(false);
            $table->text('description')->nullable(false);
            $table->text('mail')->nullable(false);
            $table->text('phone')->nullable(false);
            $table->text('site')->nullable(false);
            $table->boolean('active');
            $table->enum('level', ["JUNIOR", "STAFF", "SENIOR"])->nullable(false);
            $table->enum('scholarship', ["HIGHSCHOOL", "BACHELOR", "MASTER"])->nullable(false);
            $table->text('tech_stack')->nullable(false);
            $table->integer('city_id')->unsigned();
            $table->integer('registered_by')->unsigned();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->dateTime('deleted_at')->nullable();
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
