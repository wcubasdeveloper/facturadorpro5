<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class AddSimplifyToDocumentary extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('documentary_files', function (Blueprint $table) {
                //
                $table->unsignedTinyInteger('is_archive')->default(0)->comment('define si el tramite es simplificado');
                $table->unsignedTinyInteger('is_simplify')->default(0)->comment('define si el tramite es simplificado');
                $table->unsignedInteger('documentary_guides_number_status_id')->default(0)->comment('Cuando es simplificado, se usará este status');
            });
            Schema::table('documentary_guides_number', function (Blueprint $table) {

                $table->dateTime('date_of_due')->nullable();
                $table->longText('observation')->nullable();
                $table->longText('description')->nullable();
                $table->dateTime('date_take')->nullable()->comment('Fecha estimada de finalización');
                $table->dateTime('date_end')->nullable()->comment('Fecha de finalización');
                $table->unsignedInteger('documentary_guides_number_status_id')->default(0)->nullable()->comment('relacionado con documentary_guides_number_status');
                $table->unsignedInteger('user_id')->default(0)->comment('Responsable');
            });

            Schema::create('documentary_guides_number_status', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
            });

            DB::table('documentary_guides_number_status')->insert([
                ['id' => '1', 'name' => 'En Calificación'],
                ['id' => '2', 'name' => 'Concluidos'],
                ['id' => '3', 'name' => 'Observados'],
                ['id' => '4', 'name' => 'Archivados'],
            ]);


        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('documentary_files', function (Blueprint $table) {
                //
                $table->dropColumn('is_simplify');
                $table->dropColumn('documentary_guides_number_status_id');
                $table->dropColumn('is_archive');

            });
            Schema::table('documentary_files', function (Blueprint $table) {
                //
                $table->dropColumn('date_of_due');
                $table->dropColumn('observation');
                $table->dropColumn('description');
                $table->dropColumn('date_take');
                $table->dropColumn('date_end');
                $table->dropColumn('documentary_guides_number_status_id');
                $table->dropColumn('user_id');

            });
            Schema::dropIfExists('documentary_guides_number_status');


        }
    }
