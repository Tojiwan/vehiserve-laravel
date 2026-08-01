<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ManagesDrivers;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    use ManagesDrivers;

    protected function getViewPath(string $view): string
    {
        return "staff.drivers.{$view}";
    }

    protected function getRouteName(string $route): string
    {
        return "staff.drivers.{$route}";
    }
}