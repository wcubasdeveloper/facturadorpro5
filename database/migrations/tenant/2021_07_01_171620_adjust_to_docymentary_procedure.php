<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class AdjustToDocymentaryProcedure extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            Schema::table('documentary_offices', function (Blueprint $table) {
                $table->dropColumn(['parent_id', 'order',]);

                $table->unsignedMediumInteger('days')->default(0);
                $table->unsignedTinyInteger('default')->default(0);
            });

            /** Genera los requerimientos por proceso. */
            Schema::create('documentary_files_requirements', function (Blueprint $table) {
                $table->increments('id');
                $table->longText('name')->nullable()->comment('Nombre a mostrar');
                $table->unsignedTinyInteger('file')->default(0)->comment('Define si tiene archivo');

                $table->timestamps();
            });

            /** Relaciona requerimientos y procesos */
            Schema::create('documentary_processes_rel_req', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('doc_processes_id')->default(0)->nullable()
                      ->comment('Requerimiento relacionado');
                $table->unsignedInteger('doc_files_requirements_id')->default(0)->nullable()
                      ->comment('Proceso relacionado');
                $table->unsignedTinyInteger('active')->default(0)->comment('Status de la relacion');
                $table->timestamps();

            });


            /** Relaciona Etapas y procesos */
            Schema::create('documentary_processes_rel_off', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('doc_processes_id')->default(0)->nullable()
                      ->comment('Requerimiento relacionado');
                $table->unsignedInteger('doc_offices_id')->default(0)->nullable()
                      ->comment('Proceso relacionado');

                $table->unsignedTinyInteger('active')->default(0)->comment('Status de la relacion');
                $table->unsignedInteger('order')->default(1)->nullable()->comment('Establece el orden del proceso');
                $table->timestamps();

            });

            /** Relacion Expedientes y procesos */
            Schema::create('documentary_processes_rel_file', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('doc_processes_id')->default(0)->nullable()
                      ->comment('Requerimiento relacionado');
                $table->unsignedInteger('doc_file_id')->default(0)->nullable()
                      ->comment('Expediente relacionado');

                $table->unsignedInteger('doc_office_id')->default(0)->nullable()->comment('Etapa actual');
                $table->longText('stages')->nullable()->comment('Conjunto de etapas.');
                $table->unsignedTinyInteger('complete')->default(0)->comment('Define si se ha completado');
                $table->timestamps();

            });
            Schema::create('documentary_observation', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('doc_file_id')->default(0)->nullable()
                      ->comment('Expediente relacionado');
                $table->longText('observation')->nullable()->comment('Conjunto de etapas.');
                $table->timestamps();

            });


        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            Schema::table('documentary_offices', function (Blueprint $table) {
                //
                $table->unsignedInteger('parent_id')->default(0)->after('description');
                $table->unsignedInteger('order')->default(0)->after('description');
                $table->dropColumn('days');
                $table->dropColumn('default');
            });
            Schema::dropIfExists('documentary_files_requirements');
            Schema::dropIfExists('documentary_processes_rel_req');
            Schema::dropIfExists('documentary_processes_rel_off');
            Schema::dropIfExists('documentary_processes_rel_file');
            Schema::dropIfExists('documentary_observation');

        }
    }
