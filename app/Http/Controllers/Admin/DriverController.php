<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ManagesDrivers;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    use ManagesDrivers;

    protected function getViewPath(string $view): string
    {
        return "admin.drivers.{$view}";
    }

    protected function getRouteName(string $route): string
    {
        return "admin.drivers.{$route}";
    }
}