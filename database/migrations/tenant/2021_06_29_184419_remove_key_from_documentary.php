<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class RemoveKeyFromDocumentary extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {

            Schema::table('documentary_file_offices', function (Blueprint $table) {
                $table->dropForeign('documentary_file_offices_documentary_action_id_foreign');
                $table->dropForeign('documentary_file_offices_documentary_file_id_foreign');
                $table->dropForeign('documentary_file_offices_documentary_office_id_foreign');
                //




            });


        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            Schema::table('documentary_file_offices', function (Blueprint $table) {
                //

                $table->foreign('documentary_file_id')->on('documentary_files')->references('id')->onDelete('cascade');
                $table->foreign('documentary_office_id')->on('documentary_offices')->references('id')
                      ->onDelete('cascade');
                $table->foreign('documentary_action_id')->on('documentary_actions')->references('id')
                      ->onDelete('cascade');
            });
        }
    }
