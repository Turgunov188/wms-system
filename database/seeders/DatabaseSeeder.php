<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        try {
            DB::beginTransaction();

            //init units
            $initial_units = Unit::initialUnits();
            foreach ($initial_units as $i_unit){
                Unit::create($i_unit);
            }

            DB::commit();
        }catch (\Exception $exception){

            DB::rollback();
            echo $exception->getMessage();

        }

    }
}
