<?php

    namespace Modules\FullSuscription\Database\Seeders;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Seeder;

    class SuscriptionDatabaseSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            Model::unguard();

            // $this->call("OthersTableSeeder");
        }
    }
