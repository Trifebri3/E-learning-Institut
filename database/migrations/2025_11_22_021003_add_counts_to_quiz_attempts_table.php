<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('quiz_attempts', function (Blueprint $table) {
        $table->integer('correct_count')->nullable()->after('score');
        $table->integer('wrong_count')->nullable()->after('correct_count');
    });
}
public function down()
{
    Schema::table('quiz_attempts', function (Blueprint $table) {
        $table->dropColumn(['correct_count','wrong_count']);
    });
}

};
