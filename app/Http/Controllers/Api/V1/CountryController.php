<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;
use App\Models\Country;

class CountryController extends Controller
{

    //index
    public function index()
    {
        $countries = Country::with('currency')->orderBy('name')->get();

        return response()->json($countries);
    }
}
