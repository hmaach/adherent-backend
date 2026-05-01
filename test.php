<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Login as user 2
$user = App\Models\User::find(2);

$request = Illuminate\Http\Request::create('/api/adherent/4', 'GET');
$request->setUserResolver(function() use ($user) {
    return $user;
});

// For sanctum we might need to authenticate properly or just hit the controller directly
try {
    $adherent = app(App\Http\Controllers\AdherentController::class)->index('4');
    echo json_encode($adherent);
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
