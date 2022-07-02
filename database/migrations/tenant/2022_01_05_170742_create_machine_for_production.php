<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateMachineForProduction extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {

            Schema::create('machine_types', function (Blueprint $table) {
                //
                $table->increments('id');
                $table->text('name');
                $table->text('description')->nullable();
                $table->boolean('active')->default(0);
                $table->timestamps();
            });
            Schema::create('machines', function (Blueprint $table) {
                //
                $table->increments('id');
                $table->unsignedInteger('machine_type_id');
                $table->text('name')->nullable();
                $table->text('brand')->nullable();
                $table->text('model')->nullable();
                $table->text('closing_force')->nullable();

                $table->timestamps();

                // $table->foreign('machine_type_id')->references('id')->on('machine_types')->onDelete('cascade');
            });
            Schema::table('production', function (Blueprint $table) {
                $table->unsignedInteger('machine_id')->default(0);
                $table->string('production_order')->nullable();
                $table->string('name')->nullable();
                $table->longText('comment')->nullable();
                $table->date('date_start')->nullable();
                $table->time('time_start')->nullable();
                $table->date('date_end')->nullable();
                $table->time('time_end')->nullable();

                // $table->foreign('machine_id')->references('id')->on('machines');

            });
            Schema::table('mill', function (Blueprint $table) {
                $table->longText('comment')->nullable();

            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('machine_types');
            Schema::dropIfExists('machines');
            Schema::table('machines', function (Blueprint $table) {
                $table->dropColumn('comment');
                $table->dropColumn('machine_id');
                $table->dropColumn('production_order');
                $table->dropColumn('name');
                $table->dropColumn('date_start');
                $table->dropColumn('time_start');
                $table->dropColumn('date_end');
                $table->dropColumn('time_end');
            });
            Schema::table('mill', function (Blueprint $table) {
                $table->dropColumn('comment');
            });

        }
    }
