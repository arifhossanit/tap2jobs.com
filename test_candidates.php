<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$candidates = \DB::table('users')
    ->join('candidates', 'candidates.user_id', '=', 'users.id')
    ->where('users.is_active', true)
    ->select('candidates.id', \DB::raw("CONCAT(users.first_name, ' ', IFNULL(users.last_name, '')) as full_name"))
    ->pluck('full_name', 'id');

print_r($candidates->toArray());

$companies = \DB::table('users')
    ->join('companies', 'companies.user_id', '=', 'users.id')
    ->where('users.is_active', true)
    ->select('companies.id', \DB::raw("CONCAT(users.first_name, ' ', IFNULL(users.last_name, '')) as full_name"))
    ->pluck('full_name', 'id');

echo "Companies count: " . count($companies);
