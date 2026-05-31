<?php
namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO: app/Http/Controllers/Api/V1/MateriaController.php
// CU-15: Gestionar materias
// Regla de negocio: exactamente 4 materias del examen FICCT
//   Computación | Matemáticas | Inglés | Física
// Se bloquea crear más de 4
// ============================================================

use App\Http\Controllers\Controller;
use App\Models\Materia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => Materia::orderBy('id_materia')->get(),
            'total'   => Materia::count(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        // Bloquear si ya hay 4 materias
        if (Materia::count() >= 4) {
            return response()->json([
                'success' => false,
                'message' => 'El sistema solo permite exactamente 4 materias de examen.',
            ], 409);
        }

        $request->validate([
            'txt_nombre' => ['required', 'string', 'max:100', 'unique:tbl_materia,txt_nombre'],
        ]);

        $materia = Materia::create(['txt_nombre' => $request->txt_nombre]);

        return response()->json(['success' => true, 'data' => $materia], 201);
    }

    public function show(int $id): JsonResponse
    {
        $materia = Materia::find($id);
        if (! $materia) {
            return response()->json(['success' => false, 'message' => 'Materia no encontrada.'], 404);
        }
        return response()->json(['success' => true, 'data' => $materia]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $materia = Materia::find($id);
        if (! $materia) {
            return response()->json(['success' => false, 'message' => 'Materia no encontrada.'], 404);
        }

        $request->validate([
            'txt_nombre' => [
                'required', 'string', 'max:100',
                \Illuminate\Validation\Rule::unique('tbl_materia', 'txt_nombre')->ignore($id, 'id_materia'),
            ],
        ]);

        $materia->update(['txt_nombre' => $request->txt_nombre]);
        return response()->json(['success' => true, 'data' => $materia]);
    }

    public function destroy(int $id): JsonResponse
    {
        $materia = Materia::find($id);
        if (! $materia) {
            return response()->json(['success' => false, 'message' => 'Materia no encontrada.'], 404);
        }

        // Verificar que no tenga detalles de evaluación registrados
        if ($materia->detallesEvaluacion()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la materia porque tiene evaluaciones registradas.',
            ], 409);
        }

        $materia->delete();
        return response()->json(['success' => true, 'message' => 'Materia eliminada.']);
    }
}
