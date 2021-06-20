<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tools;
use Illuminate\Support\Facades\DB;

class WebsiteController extends Controller {

    public function index() {
        $data = tools::all();

        return view('home', compact('data'));
    }

    public function aboutUs() {
        return view('about_us');
    }

  

}
