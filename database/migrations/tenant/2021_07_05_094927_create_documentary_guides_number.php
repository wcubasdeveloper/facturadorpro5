<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateDocumentaryGuidesNumber extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {

            Schema::create('documentary_guides_number', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('doc_file_id')->default(0)->nullable()
                      ->comment('Expediente relacionado');
                $table->unsignedInteger('doc_office_id')->default(0)->nullable()->comment('Etapa observada');
                $table->longText('guide')->nullable()->comment('Especifica la guia');
                $table->longText('origin')->nullable()->comment('Especifica la instucion');
                $table->timestamps();
            });

            Schema::table('documentary_files', function (Blueprint $table) {
                $table->longText('invoice')->change();
                $table->unsignedInteger('documentary_document_id')->default(0)->nullable()->change();
                $table->dropForeign('documentary_files_documentary_document_id_foreign');
            });


        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            Schema::dropIfExists('documentary_guides_number');
            Schema::table('documentary_files', function (Blueprint $table) {
                // $table->foreign('documentary_document_id')->on('documentary_documents')->references('id')->onDelete('cascade');

            });
        }
    }
