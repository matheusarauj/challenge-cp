<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    const TABLE_NAME = 'users';

    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable(false);
                $table->string('email')->unique()->nullable(false);
                $table->string('password')->nullable(false);
                $table->string('photo')->nullable(false);
                $table->boolean('verified')->nullable(false)->default(false);
                $table->timestamps();
                $table->softDeletes();
            }
        );
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
