<?php

use Illuminate\Database\Seeder;

class lu_app_sections extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lu_app_sections')->insert(['name' => 'Style suggest']);
        DB::table('lu_app_sections')->insert(['name' => 'Trending']);
        DB::table('lu_app_sections')->insert(['name' => 'My Requests']);
        DB::table('lu_app_sections')->insert(['name' => 'My Products']);
        DB::table('lu_app_sections')->insert(['name' => 'Ask Advice']);
        DB::table('lu_app_sections')->insert(['name' => 'Ask Look']);
        DB::table('lu_app_sections')->insert(['name' => 'Ask Product']);
        DB::table('lu_app_sections')->insert(['name' => 'Stylist']);
    }
}
