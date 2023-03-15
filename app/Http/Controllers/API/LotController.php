<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LotController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $countries = \App\Models\Country::orderBy('name')->simplePaginate((int) $request->get('limit', 10));
        return response()->json($countries)->withCallback($request->callback);
    }
}
