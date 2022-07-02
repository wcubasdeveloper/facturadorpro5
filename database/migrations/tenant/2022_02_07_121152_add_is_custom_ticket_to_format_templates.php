<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsCustomTicketToFormatTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('format_templates', function (Blueprint $table) {
            $table->boolean('is_custom_ticket')->default(0);
        });

        // a futuro debemos pasar esta informacion al campo urls
        DB::table('format_templates')
                ->where('formats', 'default')
                ->update(['is_custom_ticket' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('format_templates', function (Blueprint $table) {
            $table->dropColumn('is_custom_ticket');
        });
    }
}
