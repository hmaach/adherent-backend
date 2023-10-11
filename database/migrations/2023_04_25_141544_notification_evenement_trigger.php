<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    //     DB::unprepared('
    //     CREATE TRIGGER add_evenemnet_notif
    //     AFTER INSERT ON `evenements`
    //     FOR EACH ROW
    //             BEGIN
    //                INSERT INTO `notifications` (`evenement_id`,`dateNotif`) VALUES (NEW.id,CURTIME());
    //             END
    //     ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER `add_evenemnet_notif`');
    }
};
