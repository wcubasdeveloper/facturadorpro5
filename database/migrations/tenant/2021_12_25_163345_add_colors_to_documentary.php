<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class AddColorsToDocumentary extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('documentary_guides_number_status', function (Blueprint $table) {
                //
                $table->string('color')->nullable();
            });
            Schema::table('documentary_offices', function (Blueprint $table) {
                //
                $table->string('color')->nullable();
            });
            Schema::table('documentary_guides_number', function (Blueprint $table) {
                //
                $table->unsignedInteger('total_day')->nullable()->default(1);
            });

        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('documentary_guides_number_status', function (Blueprint $table) {
                //
                $table->dropColumn('color');
            });
            Schema::table('documentary_offices', function (Blueprint $table) {
                //
                $table->dropColumn('color');
            });
            Schema::table('documentary_guides_number', function (Blueprint $table) {
                //
                $table->dropColumn('total_day');
            });
        }
    }
