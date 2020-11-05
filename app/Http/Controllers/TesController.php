<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class TesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function testing(Request $request)
    {
        $save = \App\Tescrud::create($request->all());
        return response()->json('OK');
    }

    //
}
