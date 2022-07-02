<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class ChangeObservationFromFileOffices extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            Schema::table('documentary_file_offices', function (Blueprint $table) {
                if(Schema::hasColumn('documentary_file_offices','observation')) {
                    $table->dropColumn('observation');
                }
            });
            Schema::table('documentary_file_offices', function (Blueprint $table) {
                if(!Schema::hasColumn('documentary_file_offices','observation')) {
                    $table->integer('observation');
                }
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            Schema::table('documentary_file_offices', function (Blueprint $table) {
                Schema::table('documentary_file_offices', function (Blueprint $table) {
                    $table->dropColumn('observation');
                });
            });
        }
    }
