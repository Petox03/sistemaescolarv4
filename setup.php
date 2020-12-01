<?php
use Illuminate\Database\Capsule\Manager as DB;
require_once 'functions.php';
require "vendor/autoload.php";
require "config/database.php";

DB::schema()->create('users', function ($table) {
    $table->integer('id_user')->autoIncrement();
    $table->string('user')->unique();
    $table->string('pass');
    $table->integer('idaccess');
    $table->string('name');
    $table->string('lastname');
});

    DB::schema()->create('materias', function ($table) {
    $table->foreignId('users_id_user');
    $table->integer('español');
    $table->integer('matematicas');
    $table->integer('historia');
});

DB::table('users')->insert(['user' => 'admin', 'pass' => '123',' idaccess' => '1', 'name' => 'Uriel', 'lastname' => 'Ceron']);

?>