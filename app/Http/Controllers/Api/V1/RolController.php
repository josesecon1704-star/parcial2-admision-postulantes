<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// CU-05: Asignar roles
//   GET   /api/v1/roles              → index       (listar roles)
//   GET   /api/v1/roles/{id}         → show        (ver rol)
//   PATCH /api/v1/usuarios/{id}/rol  → asignarRol  (cambiar rol)
//
// Nota: Los roles son un catálogo fijo (5 roles del sistema).
// No se crean ni eliminan desde la app — solo se asignan a usuarios.
// ============================================================

use App\Http\Controllers\Controller;
use App\Http\Requests\AsignarRolRequest;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;

class RolController extends Controller
{
    // ──────────────────────────────────────────────────────
    // GET /api/v1/roles
    // Listar todos los roles disponibles del sistema
    // Usado por el frontend para llenar el <select> de roles
    // ──────────────────────────────────────────────────────
    public function index(): JsonResponse
    {
        $roles = Rol::select('id_rol', 'txt_nombre', 'txt_descripcion')
                    ->orderBy('id_rol')
                    ->get();

        return response()->json([
            'success' => true,
            'data'    => $roles,
        ], 200);
    }

    // ──────────────────────────────────────────────────────
    // GET /api/v1/roles/{id}
    // Ver un rol con sus usuarios asignados
    // ──────────────────────────────────────────────────────
    public function show(int $id): JsonResponse
    {
        $rol = Rol::with([
            'usuarios:id_usuario,txt_username,txt_email,bol_estado,id_rol',
        ])->find($id);

        if (! $rol) {
            return response()->json([
                'success' => false,
                'message' => 'Rol no encontrado.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'id_rol'          => $rol->id_rol,
                'txt_nombre'      => $rol->txt_nombre,
                'txt_descripcion' => $rol->txt_descripcion,
                'total_usuarios'  => $rol->usuarios->count(),
                'usuarios'        => $rol->usuarios,
            ],
        ], 200);
    }

    // ──────────────────────────────────────────────────────
    // PATCH /api/v1/usuarios/{id}/rol
    // CU-05: Asignar o cambiar el rol de un usuario
    // Body: { "id_rol": 2 }
    // ──────────────────────────────────────────────────────
    public function asignarRol(AsignarRolRequest $request, int $idUsuario): JsonResponse
    {
        $usuario = Usuario::with('rol')->find($idUsuario);

        if (! $usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        // Evitar que el admin cambie su propio rol accidentalmente
        if ($usuario->id_usuario === auth('api')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes cambiar tu propio rol.',
            ], 403);
        }

        $rolAnterior = $usuario->rol->txt_nombre;

        $usuario->update([
            'id_rol'            => $request->id_rol,
            'fch_actualizacion' => now(),
        ]);

        $rolNuevo = Rol::find($request->id_rol);

        return response()->json([
            'success' => true,
            'message' => "Rol cambiado de {$rolAnterior} a {$rolNuevo->txt_nombre}.",
            'data'    => [
                'id_usuario'   => $usuario->id_usuario,
                'txt_username' => $usuario->txt_username,
                'rol_anterior' => $rolAnterior,
                'rol_nuevo'    => $rolNuevo->txt_nombre,
            ],
        ], 200);
    }
}
