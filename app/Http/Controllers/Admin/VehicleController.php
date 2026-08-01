<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ManagesVehicles;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    use ManagesVehicles;

    protected function getViewPath(string $view): string
    {
        return "admin.vehicles.{$view}";
    }

    protected function getRouteName(string $route): string
    {
        return "admin.vehicles.{$route}";
    }
}