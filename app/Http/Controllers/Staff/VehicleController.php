<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ManagesVehicles;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    use ManagesVehicles;

    protected function getViewPath(string $view): string
    {
        return "staff.vehicles.{$view}";
    }

    protected function getRouteName(string $route): string
    {
        return "staff.vehicles.{$route}";
    }
}