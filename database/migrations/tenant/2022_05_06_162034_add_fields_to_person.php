<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class AddFieldsToPerson extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
                Schema::create('full_suscription_user_data', function (Blueprint $table) {
                    $table->increments('id');
                    $table->unsignedInteger('person_id')->nullable()->default(0);
                    $table->string('discord_user')->nullable();
                    $table->string('slack_channel')->nullable();
                    $table->string('discord_channel')->nullable();
                    $table->string('gitlab_user')->nullable();

                    $table->timestamps();
                });
                Schema::create('full_suscription_server_data', function (Blueprint $table) {
                    $table->increments('id');
                    $table->unsignedInteger('person_id')->nullable()->default(0);
                    $table->string('host')->nullable();
                    $table->ipAddress('ip')->nullable();
                    $table->string('user')->nullable();
                    $table->string('password')->nullable();
                    $table->timestamps();
                });
            Schema::table('user_rel_suscription_plans', function (Blueprint $table) {
                $table->longText('children_customer')->nullable()->change();

            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('full_suscription_user_data');
            Schema::dropIfExists('full_suscription_server_data');

        }
    }
