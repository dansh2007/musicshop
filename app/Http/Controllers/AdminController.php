<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Instrument;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats = [
            'instruments' => Instrument::count(),
            'categories' => Category::count(),
            'brands' => Brand::count(),
            'users' => User::count(),
        ];

        $recentInstruments = Instrument::with(['brand', 'category'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentInstruments'));
    }

    public function __call($name, $arguments)
    {
        abort(404);
    }
}
