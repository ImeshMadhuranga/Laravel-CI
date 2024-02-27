<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnFiveFive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('settings') ) {
            Schema::table('settings', function (Blueprint $table) {
                if (!Schema::hasColumn('settings', 'verify_status'))
                {
                    $table->enum('verify_status',['1','0'])->default(0);
                }
                if (!Schema::hasColumn('settings', 'verify_message'))
                {
                    $table->string('verify_message')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
