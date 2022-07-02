<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class AddFieldsToDocumentaryOffice extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            Schema::table('documentary_offices', function (Blueprint $table) {
                //

                $table->unsignedInteger('parent_id')->default(0)->after('description');
                $table->unsignedInteger('order')->default(0)->after('description');
            });
            Schema::table('documentary_processes', function (Blueprint $table) {
                $table->decimal('price', 10, 5)->default(0)->after('description');
            });
            Schema::create('rel_user_to_documentary_offices', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedTinyInteger('active')->default(0);
                $table->unsignedInteger('user_id')->default(0)->comment('usuario asociado');
                $table->unsignedInteger('documentary_office_id')->default(0)->comment('etapa asociada');
                $table->timestamps();
            });


            Schema::create('documentary_files_archives', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->default(0)->comment('usuario asociado');
                $table->unsignedInteger('documentary_file_id')->default(0)->comment('Solicitud asociada');
                $table->unsignedInteger('documentary_office_id')->default(0)->comment('etapa asociada');
                $table->longText('observation')->nullable()->comment('observacion');
                $table->longText('attached_file')->nullable()->comment('etapa asociada');

                $table->timestamps();
            });

            Schema::table('documentary_files', function (Blueprint $table) {
                $table->unsignedInteger('documentary_office_id')->default(0)->after('status')->comment('Define el ultimo proceso');
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
                $table->dropColumn([
                                       'parent_id',
                                       'order',
                                   ]);
            });
            Schema::table('documentary_processes', function (Blueprint $table) {
                //
                $table->dropColumn([
                                       'price',
                                   ]);
            });
            Schema::table('documentary_files', function (Blueprint $table) {
                //
                $table->dropColumn([
                                       'documentary_office_id',
                                   ]);
            });
            Schema::dropIfExists('rel_user_to_documentary_offices');
            Schema::dropIfExists('documentary_files_archives');

        }
    }
