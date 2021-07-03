<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_types', function (Blueprint $table) {
            $table->id()->startingValue(1);
            $table->text("name");
            $table->timestamps();
        });

        DB::table('event_types')->insert(['name' => 'logged in', 'id' => 1]);
        DB::table('event_types')->insert(['name' => 'logged out', 'id' => 2]);
        DB::table('event_types')->insert(['name' => 'registered', 'id' => 3]);
        DB::table('event_types')->insert(['name' => 'registration confirmed', 'id' => 4]);
        DB::table('event_types')->insert(['name' => 'password reset request', 'id' => 5]);
        DB::table('event_types')->insert(['name' => 'password reset', 'id' => 6]);
        DB::table('event_types')->insert(['name' => 'email sent', 'id' => 7]);
        DB::table('event_types')->insert(['name' => 'settings saved', 'id' => 8]);
        DB::table('event_types')->insert(['name' => 'account deleted', 'id' => 9]);
        DB::table('event_types')->insert(['name' => 'generic event', 'id' => 10]);
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_types');
    }
}
