<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->timestamp('last_activity')->nullable()->after('fecha_registro');
            $table->boolean('is_online')->default(false)->after('last_activity');
        });
    }

    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['last_activity', 'is_online']);
        });
    }
};
