<?php
namespace App\Http\Controllers\Api\V1;

// ============================================================
// CU-17 parcial — catálogo de turnos (Mañana, Tarde, Noche)
// GET  /api/v1/turnos       → index
// POST /api/v1/turnos       → store
// ============================================================

use App\Http\Controllers\Controller;
use App\Models\Turno;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => Turno::orderBy('id_turno')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'txt_turno' => ['required', 'string', 'max:50', 'unique:tbl_turno,txt_turno'],
        ]);

        $turno = Turno::create(['txt_turno' => $request->txt_turno]);

        return response()->json(['success' => true, 'data' => $turno], 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $turno = Turno::find($id);
        if (! $turno) {
            return response()->json(['success' => false, 'message' => 'Turno no encontrado.'], 404);
        }
        $turno->delete();
        return response()->json(['success' => true, 'message' => 'Turno eliminado.']);
    }
}
