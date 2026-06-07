<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (\App\Models\TempatIbadah::all() as $t) {
    if (!$t->kode_lapor) {
        $t->update(['kode_lapor' => \Illuminate\Support\Str::random(10)]);
        echo "Updated {$t->id} with {$t->kode_lapor}\n";
    }
}
