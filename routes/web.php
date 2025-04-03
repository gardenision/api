<?php

use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/token', function (Request $request) {
    return response()->json([
        'token' => $request->session()->token(),
    ]);
});