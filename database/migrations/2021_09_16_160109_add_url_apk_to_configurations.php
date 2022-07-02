<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;
    use App\Models\System\Configuration;

    class AddUrlApkToConfigurations extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('configurations', function (Blueprint $table) {
                $table->text('apk_url')->nullable();
            });

        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('configurations', function (Blueprint $table) {
                $table->dropColumn('apk_url');
            });
        }
    }
