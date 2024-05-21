<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorizationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique()->index();
            $table->string('nama')->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
            
        });

        Schema::create('permission', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique()->index();
            $table->text('nama')->nullable();
            $table->string('action')->nullable();
            $table->string('group')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });

        Schema::create('scope', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique()->index();
            $table->string('nama')->nullable();
            $table->string('akronim')->nullable();
            $table->string('kode')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });

        Schema::create('menu_group', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama')->nullable();
            $table->string('nama_en')->nullable();
            $table->integer('urutan');
            $table->tinyInteger('is_tampil')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });

        Schema::create('menu', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('menu_group_id')->index()->comment('diisi slug menu_group');
            $table->string('nama')->nullable();
            $table->string('nama_en')->nullable();
            $table->string('icon')->nullable();
            $table->integer('urutan');
            $table->tinyInteger('is_tampil')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });

        Schema::create('module', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('menu_id')->index()->comment('diisi slug menu');
            $table->string('nama')->nullable();
            $table->string('routing')->nullable();
            $table->string('permission')->nullable();
            $table->integer('urutan');
            $table->tinyInteger('is_tampil')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            // $table->uuid('id')->primary();
            $table->uuid('permission_id');
            $table->uuid('role_id');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();

            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('permission_user', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('permission_id');
            $table->uuid('user_id');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('role_id');
            $table->uuid('user_id');
            $table->uuid('scope_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('permission');
        Schema::dropIfExists('role');
        Schema::dropIfExists('menu');
        Schema::dropIfExists('menu_group');
        Schema::dropIfExists('scope');
        Schema::dropIfExists('module');
    }
}
