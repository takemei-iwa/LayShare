<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Layout;

class LayoutController extends Controller
{   
    public function index()
    {
        return view('layouts.index');
    }
}
