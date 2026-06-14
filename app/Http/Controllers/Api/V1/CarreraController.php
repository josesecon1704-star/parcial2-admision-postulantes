<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Models\Carrera;
use Illuminate\Http\JsonResponse;

class CarreraController extends Controller
{
    public function index(): JsonResponse
    {
        $carreras = Carrera::orderBy('txt_nombre')->get([
            'id_carrera', 'txt_nombre', 'int_cupo'
        ]);

        return response()->json([
            'success' => true,
            'data'    => $carreras,
        ]);
    }
}
