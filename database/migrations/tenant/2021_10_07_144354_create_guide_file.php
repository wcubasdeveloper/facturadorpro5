<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateGuideFile extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('guide_files', function (Blueprint $table) {
                $table->increments('id');
                $table->text('filename')->nullable()->comment('Nombre de archivo');
                $table->unsignedInteger('purchase_id')->nullable()->default(0)->comment("Relacion con purchases");
                $table->unsignedInteger('document_id')->nullable()->default(0)->comment("Relacion con documents");
                $table->unsignedInteger('order_note_id')->nullable()->default(0)->comment("Relacion con order_notes");
                $table->unsignedInteger('quotation_id')->nullable()->default(0)->comment("Relacion con quotations");
                $table->unsignedInteger('sale_note_id')->nullable()->default(0)->comment("Relacion con sale_notes");
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('guide_files');
        }
    }
