<?php

namespace App\Http\Controllers;

use App\Models\Demo;
use Illuminate\Http\Request;

class DemoController extends Controller 
{
    public function index(Request $request)
    {
        $query = Demo::query();
        
        if ($request->input('server')) {
            $query->where('server_name', $request->input('server'));
        }
        
        if ($request->input('map')) {
            $query->where('map', $request->input('map'));
        }
        
        if ($request->input('date')) {
            $query->whereDate('date', $request->input('date'));
        }

        $demos = $query->orderBy('date', 'desc')->get();
        $maps = Demo::distinct()->pluck('map');

        return view('demo.demo', compact('demos', 'maps'));
    }
}