<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dateTime('start_time')->nullable()->after('host_id');
            $table->integer('duration_minutes')->default(60)->after('start_time');
        });
    }

    public function down() {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'duration_minutes']);
        });
    }
};
