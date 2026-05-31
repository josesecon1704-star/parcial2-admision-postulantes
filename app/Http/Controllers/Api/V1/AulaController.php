<?php
namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO: app/Http/Controllers/Api/V1/AulaController.php
// CU-16: Gestionar aulas
// GET    /api/v1/aulas        → index
// POST   /api/v1/aulas        → store
// PUT    /api/v1/aulas/{id}   → update
// DELETE /api/v1/aulas/{id}   → destroy
// ============================================================

use App\Http\Controllers\Controller;
use App\Models\Aula;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AulaController extends Controller
{
    public function index(): JsonResponse
    {
        $aulas = Aula::orderBy('int_piso')->orderBy('txt_nro_aula')->get();
        return response()->json([
        'success' => true,
        'data' => \App\Models\Aula::all()
    ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'int_piso'    => ['required', 'integer', 'min:0'],
            'txt_nro_aula'=> [
                'required', 'string', 'max:20',
                // UNIQUE: (int_piso, txt_nro_aula) — mismo que constraint PostgreSQL
                Rule::unique('tbl_aula')->where(fn($q) =>
                    $q->where('int_piso', $request->int_piso)
                ),
            ],
        ], [
            'txt_nro_aula.unique' => 'Ya existe ese número de aula en ese piso.',
        ]);

        $aula = Aula::create($request->only('int_piso', 'txt_nro_aula'));
        return response()->json(['success' => true, 'data' => $aula], 201);
    }

    public function show(int $id): JsonResponse
    {
        $aula = Aula::find($id);
        if (! $aula) {
            return response()->json(['success' => false, 'message' => 'Aula no encontrada.'], 404);
        }
        return response()->json(['success' => true, 'data' => $aula]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $aula = Aula::find($id);
        if (! $aula) {
            return response()->json(['success' => false, 'message' => 'Aula no encontrada.'], 404);
        }

        $request->validate([
            'int_piso'    => ['sometimes', 'integer', 'min:0'],
            'txt_nro_aula'=> [
                'sometimes', 'string', 'max:20',
                Rule::unique('tbl_aula')
                    ->where(fn($q) => $q->where('int_piso', $request->int_piso ?? $aula->int_piso))
                    ->ignore($id, 'id_aula'),
            ],
        ]);

        $aula->update($request->only('int_piso', 'txt_nro_aula'));
        return response()->json(['success' => true, 'data' => $aula]);
    }

    public function destroy(int $id): JsonResponse
    {
        $aula = Aula::find($id);
        if (! $aula) {
            return response()->json(['success' => false, 'message' => 'Aula no encontrada.'], 404);
        }
        $aula->delete();
        return response()->json(['success' => true, 'message' => 'Aula eliminada.']);
    }
}
