<?php
namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO: app/Http/Controllers/Api/V1/HorarioController.php
// CU-17: Gestionar horarios
// Tabla: tbl_horario — txt_dia_semana | tm_hora_inicio | tm_hora_final | id_turno
// CHECK en PostgreSQL: tm_hora_final > tm_hora_inicio
// ============================================================

use App\Http\Controllers\Controller;
use App\Models\Horario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Horario::with('turno');

        // Filtro opcional por turno
        if ($request->filled('id_turno')) {
            $query->where('id_turno', $request->id_turno);
        }

        // Filtro opcional por día
        if ($request->filled('dia')) {
            $query->where('txt_dia_semana', 'ilike', "%{$request->dia}%");
        }

        $horarios = $query->orderBy('id_turno')->orderBy('txt_dia_semana')->get();

        return response()->json(['success' => true, 'data' => $horarios]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'txt_dia_semana' => ['required', 'string', 'max:20'],
            'tm_hora_inicio' => ['required', 'date_format:H:i'],
            'tm_hora_final'  => ['required', 'date_format:H:i', 'after:tm_hora_inicio'],
            'id_turno'       => ['required', 'integer', 'exists:tbl_turno,id_turno'],
        ], [
            'tm_hora_final.after'       => 'La hora final debe ser mayor a la hora de inicio.',
            'id_turno.exists'           => 'El turno seleccionado no existe.',
        ]);

        $horario = Horario::create($validated);

        return response()->json([
            'success' => true,
            'data'    => $horario->load('turno'),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $horario = Horario::with('turno')->find($id);
        if (! $horario) {
            return response()->json(['success' => false, 'message' => 'Horario no encontrado.'], 404);
        }
        return response()->json(['success' => true, 'data' => $horario]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $horario = Horario::find($id);
        if (! $horario) {
            return response()->json(['success' => false, 'message' => 'Horario no encontrado.'], 404);
        }

        $validated = $request->validate([
            'txt_dia_semana' => ['sometimes', 'string', 'max:20'],
            'tm_hora_inicio' => ['sometimes', 'date_format:H:i'],
            'tm_hora_final'  => ['sometimes', 'date_format:H:i', 'after:tm_hora_inicio'],
            'id_turno'       => ['sometimes', 'integer', 'exists:tbl_turno,id_turno'],
        ]);

        $horario->update($validated);

        return response()->json(['success' => true, 'data' => $horario->load('turno')]);
    }

    public function destroy(int $id): JsonResponse
    {
        $horario = Horario::find($id);
        if (! $horario) {
            return response()->json(['success' => false, 'message' => 'Horario no encontrado.'], 404);
        }
        $horario->delete();
        return response()->json(['success' => true, 'message' => 'Horario eliminado.']);
    }
}
