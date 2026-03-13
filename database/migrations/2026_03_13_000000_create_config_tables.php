<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigTables extends Migration
{
    public function up()
    {
        Schema::create('config_group', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique()->index();
            $table->string('nama')->nullable();
            $table->integer('urutan');
            $table->string('icon')->nullable();
            $table->tinyInteger('is_tampil')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });

        Schema::create('config', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('config_group_id')->index();
            $table->string('config_name')->nullable();
            $table->string('key')->unique()->index();
            $table->text('default_value')->nullable();
            $table->text('current_value')->nullable();
            $table->string('form_type')->nullable();
            $table->json('form_options')->nullable();
            $table->tinyInteger('is_multiple')->default(0);
            $table->string('form_label')->nullable();
            $table->string('form_placeholder')->nullable();
            $table->string('form_help')->nullable();
            $table->string('validation_rules')->nullable();
            $table->integer('urutan')->default(0);
            $table->tinyInteger('is_tampil')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('config');
        Schema::dropIfExists('config_group');
    }
}
