<?php

namespace App\Http\Controllers;

use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::active()->with('activities')->get();

        return view('packages.index', compact('packages'));
    }

    public function show(Package $package)
    {
        $package->load('activities');

        return view('packages.show', compact('package'));
    }
}
