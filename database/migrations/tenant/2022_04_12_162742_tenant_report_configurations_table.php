<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReportConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('route_name')->unique();
            $table->string('route_path')->unique();
            $table->string('name');
            $table->boolean('convert_pen')->default(false);
            $table->timestamps();
        });

        DB::table('report_configurations')->insert([
            [
                'route_name' => 'tenant.reports.general_items.index',
                'route_path' => 'reports/general-items',
                'name' => 'Ventas - Reporte general de productos',
                'convert_pen' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'route_name' => 'tenant.reports.purchases.general_items.index',
                'route_path' => 'reports/purchases/general_items',
                'name' => 'Compras - Reporte general de productos',
                'convert_pen' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'route_name' => 'tenant.reports.purchases.index',
                'route_path' => 'reports/purchases',
                'name' => 'Compras - Compras totales',
                'convert_pen' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_configurations');
    }

}
