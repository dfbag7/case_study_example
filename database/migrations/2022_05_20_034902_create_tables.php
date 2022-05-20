<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string("name", 255);
            $table->string("address", 255);
            $table->uuid('property_type');

            $table->timestamps();

            $table->unique('name');
        });

        Schema::create('property_fields', function(Blueprint $table) {
            $table->id();
            $table->string('name', 255);

            $table->timestamps();

            $table->unique('name');
        });

        Schema::create('property_field_values', function(Blueprint $table) {
            $table->id();
            $table->foreignId('property_field_id');
            $table->foreignId('property_id');
            $table->string('value', 255);

            $table->timestamps();

            $table->unique(['property_field_id', 'property_id']);

            $table->foreign('property_field_id')->references('id')->on('property_fields');
            $table->foreign('property_id')->references('id')->on('properties');
        });

        Schema::create('search_profiles', function(Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->uuid('property_type');

            $table->timestamps();

            $table->unique('name');
        });

        Schema::create('search_profile_values', function(Blueprint $table) {
            $table->id();
            $table->foreignId('search_profile_id');
            $table->foreignId('property_field_id');
            $table->enum('kind', ['direct', 'range']);
            $table->string('direct_value', 255)->nullable();
            $table->string('min_range_value', 255)->nullable();
            $table->string('max_range_value', 255)->nullable();

            $table->timestamps();

            $table->unique(['search_profile_id', 'property_field_id']);

            $table->foreign('search_profile_id')->references('id')->on('search_profiles');
            $table->foreign('property_field_id')->references('id')->on('property_fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('search_profile_fields');
        Schema::dropIfExists('search_profiles');
        Schema::dropIfExists('property_field_values');
        Schema::dropIfExists('property_fields');
    }
};
