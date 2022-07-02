<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class AddFieldToDocumentaryFile extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            Schema::getConnection()
                  ->getDoctrineSchemaManager()
                  ->getDatabasePlatform()
                  ->registerDoctrineTypeMapping('enum', 'string');

            Schema::table('documentary_file_offices', function (Blueprint $table) {
                $table->longText('office_name')->nullable()->comment('Nombre de la etapa');
                $table->longText('process_name')->nullable()->comment('Nombre del tramite');
                $table->unsignedInteger('documentary_process_id')
                      ->default(0)
                      ->comment('Tramite relacionado');
                $table->integer('complete')
                      ->default(0)
                      ->comment('Define si la etapa esta completa');

                $table->dateTime('start_date')->nullable()->comment('Fecha de inicio');
                $table->dateTime('end_date')->nullable()->comment('Fecha de finalizacion');
                $table->unsignedInteger('days')->default(0)->nullable()->comment('dias para el tramite');
            });
            Schema::table('documentary_file_offices', function (Blueprint $table) {
                $table->unsignedInteger('documentary_action_id')->default(0)->change();
            });


        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            Schema::table('documentary_files', function (Blueprint $table) {
                //
                $table->dropColumn('requirements');
            });
            Schema::table('documentary_file_offices', function (Blueprint $table) {
                $table->dropColumn('office_name');
                $table->dropColumn('process_name');
                $table->dropColumn('documentary_process_id');
                $table->dropColumn('complete');
                $table->dropColumn('start_date');
                $table->dropColumn('end_date');
                $table->dropColumn('days');
                $table->dropForeign('documentary_file_offices_documentary_process_id_foreign');
                $table->dropForeign('documentary_file_offices_documentary_file_id_foreign');
                $table->dropForeign('documentary_file_offices_documentary_office_id_foreign');
            });
        }
    }
