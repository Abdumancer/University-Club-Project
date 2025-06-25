<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovalToEventsTable extends Migration
{
    
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('club_approved')->default(false);
            $table->boolean('admin_approved')->default(false);
        });
    }

    
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['club_approved', 'admin_approved']);
        });
    }
}
