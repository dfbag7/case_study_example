<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultValues extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentTimestamp = Carbon::now();

        //

        \DB::table('property_fields')->insert([
            'id' => 1,
            'name' => 'area',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('property_fields')->insert([
            'id' => 2,
            'name' => 'yearOfConstruction',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('property_fields')->insert([
            'id' => 3,
            'name' => 'rooms',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('property_fields')->insert([
            'id' => 4,
            'name' => 'heatingType',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('property_fields')->insert([
            'id' => 5,
            'name' => 'parking',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('property_fields')->insert([
            'id' => 6,
            'name' => 'returnActual',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('property_fields')->insert([
            'id' => 7,
            'name' => 'price',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::statement('alter table property_fields auto_increment = 0');

        //

        \DB::table('properties')->insert([
            'id' => 1,
            'name' => 'Awesome house in the middle of my town',
            'address' => 'Main street 17, 12456 Berlin',
            'property_type' => 'd44d0090-a2b5-47f7-80bb-d6e6f85fca90',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::statement('alter table properties auto_increment = 0');

        //

        \DB::table('property_field_values')->insert([
            'property_field_id' => 1,
            'property_id' => 1,
            'value' => '180',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('property_field_values')->insert([
            'property_field_id' => 2,
            'property_id' => 1,
            'value' => '2010',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('property_field_values')->insert([
            'property_field_id' => 3,
            'property_id' => 1,
            'value' => '5',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('property_field_values')->insert([
            'property_field_id' => 4,
            'property_id' => 1,
            'value' => 'gas',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('property_field_values')->insert([
            'property_field_id' => 5,
            'property_id' => 1,
            'value' => 'true',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('property_field_values')->insert([
            'property_field_id' => 6,
            'property_id' => 1,
            'value' => '12.8',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('property_field_values')->insert([
            'property_field_id' => 7,
            'property_id' => 1,
            'value' => '1500000',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        //

        \DB::table('search_profiles')->insert([
            'id' => 1,
            'name' => 'Looking for any Awesome real estate!',
            'property_type' => 'd44d0090-a2b5-47f7-80bb-d6e6f85fca90',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::statement('alter table search_profiles auto_increment = 0');

        //

        \DB::table('search_profile_values')->insert([
            'search_profile_id' => 1,
            'property_field_id' => 7,
            'kind' => 'range',
            'direct_value' => null,
            'min_range_value' => '0',
            'max_range_value' => '2000000',
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('search_profile_values')->insert([
            'search_profile_id' => 1,
            'property_field_id' => 1,
            'kind' => 'range',
            'direct_value' => null,
            'min_range_value' => '150',
            'max_range_value' => null,
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('search_profile_values')->insert([
            'search_profile_id' => 1,
            'property_field_id' => 2,
            'kind' => 'range',
            'direct_value' => null,
            'min_range_value' => '2010',
            'max_range_value' => null,
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('search_profile_values')->insert([
            'search_profile_id' => 1,
            'property_field_id' => 3,
            'kind' => 'range',
            'direct_value' => null,
            'min_range_value' => '4',
            'max_range_value' => null,
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);

        \DB::table('search_profile_values')->insert([
            'search_profile_id' => 1,
            'property_field_id' => 6,
            'kind' => 'range',
            'direct_value' => null,
            'min_range_value' => '4',
            'max_range_value' => null,
            'created_at' => $currentTimestamp,
            'updated_at' => $currentTimestamp,
        ]);
    }
}
