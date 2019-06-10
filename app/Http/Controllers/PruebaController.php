<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class PruebaController extends Controller
{
    public function singleFile (Request $request)
    {
        dd($request->all());
    }
}