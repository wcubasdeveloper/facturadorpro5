<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateMiTiendaPe extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('mi_tienda_pe', function (Blueprint $table) {
                $table->increments('id');
                $table->text('order_number')->nullable()->comment('Numero de pedido');
                $table->text('transaction_code')->nullable()->comment('Codigo de pasareña');
                $table->unsignedInteger('order_note_id')->default(0)->nullable();
                $table->unsignedInteger('document_id')->default(0)->nullable();
                $table->timestamps();
            });
            Schema::table('production', function (Blueprint $table) {

                $table->string('lot_code')->nullable();
                $table->json('item_extra_data')->nullable();
                $table->date('mix_date_start')->nullable();
                $table->time('mix_time_start')->nullable();
                $table->date('mix_date_end')->nullable();
                $table->time('mix_time_end')->nullable();
                $table->unsignedTinyInteger('informative')->nullable()->default(0);
                $table->decimal('agreed')->default(0)->nullable();
                $table->decimal('imperfect')->default(0)->nullable();
                $table->longText('proccess_type')->nullable();
            });
            Schema::table('mill', function (Blueprint $table) {
                $table->string('mill_name')->nullable();
                $table->string('lot_code')->nullable();

            });

            if(!Schema::hasColumn('mill','comment')) {
                Schema::table('mill', function (Blueprint $table) {
                    $table->longText('comment')->nullable();
                });
            }

            Schema::table('mill_items', function (Blueprint $table) {
                $table->json('item_extra_data')->nullable();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('mi_tienda_pe');

            Schema::table('production', function (Blueprint $table) {
                $table->dropColumn('lot_code');
                $table->dropColumn('item_extra_data');
                $table->dropColumn('mix_date_start');
                $table->dropColumn('mix_time_start');
                $table->dropColumn('mix_date_end');
                $table->dropColumn('mix_time_end');
                $table->dropColumn('agreed');
                $table->dropColumn('imperfect');
                $table->dropColumn('proccess_type');
            });


            Schema::table('mill', function (Blueprint $table) {
                $table->dropColumn('mill_name');
                $table->dropColumn('lot_code');
                if(Schema::hasColumn('mill','comment')) {
                    $table->dropColumn('comment');
                }

            });

            Schema::table('mill_items', function (Blueprint $table) {
                $table->dropColumn('item_extra_data');
            });
        }
    }
