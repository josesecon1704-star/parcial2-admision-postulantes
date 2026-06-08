<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO: app/Http/Controllers/Api/V1/UsuarioController.php
//
// CU-04: Gestionar usuarios
//   GET    /api/v1/usuarios            → index   (listar)
//   POST   /api/v1/usuarios            → store   (crear)
//   GET    /api/v1/usuarios/{id}       → show    (ver uno)
//   PUT    /api/v1/usuarios/{id}       → update  (editar)
//   DELETE /api/v1/usuarios/{id}       → destroy (eliminar lógico)
//   PATCH  /api/v1/usuarios/{id}/estado → toggleEstado
// ============================================================

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // ──────────────────────────────────────────────────────
    // GET /api/v1/usuarios
    // Lista todos los usuarios con su rol
    // Query params opcionales: ?buscar=texto  ?rol=DOCENTE  ?estado=1
    // ──────────────────────────────────────────────────────
    public function index(Request $request): JsonResponse
    {
        $query = Usuario::with('rol')
            ->select(
                'id_usuario', 'txt_username', 'txt_email',
                'bol_estado', 'fch_ultimo_acceso', 'fch_creacion', 'id_rol'
            );

        // Filtro por texto (username o email)
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('txt_username', 'ilike', "%{$buscar}%")
                  ->orWhere('txt_email',   'ilike', "%{$buscar}%");
            });
        }

        // Filtro por nombre de rol
        if ($request->filled('rol')) {
            $query->whereHas('rol', fn($q) =>
                $q->where('txt_nombre', $request->rol)
            );
        }

        // Filtro por estado activo/inactivo
        if ($request->has('estado')) {
            $query->where('bol_estado', filter_var($request->estado, FILTER_VALIDATE_BOOLEAN));
        }

        $usuarios = $query->orderBy('fch_creacion', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $usuarios,
        ], 200);
    }

    // ──────────────────────────────────────────────────────
    // POST /api/v1/usuarios
    // Crear nuevo usuario (CU-04)
    // ──────────────────────────────────────────────────────
    public function store(StoreUsuarioRequest $request): JsonResponse
    {
        $usuario = Usuario::create([
            'id_rol'          => $request->id_rol,
            'txt_username'    => $request->txt_username,
            'txt_email'       => $request->txt_email,
            'txt_password'    => Hash::make($request->txt_password),
            'bol_estado'      => $request->bol_estado ?? true,
            'fch_creacion'    => now(),
            'fch_actualizacion' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado correctamente.',
            'data'    => $usuario->load('rol'),
        ], 201);
    }

    // ──────────────────────────────────────────────────────
    // GET /api/v1/usuarios/{id}
    // Ver detalle de un usuario
    // ──────────────────────────────────────────────────────
    public function show(int $id): JsonResponse
    {
        $usuario = Usuario::with('rol')
            ->select(
                'id_usuario', 'txt_username', 'txt_email',
                'bol_estado', 'fch_ultimo_acceso', 'fch_creacion',
                'fch_actualizacion', 'id_rol'
            )
            ->find($id);

        if (! $usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $usuario,
        ], 200);
    }

    // ──────────────────────────────────────────────────────
    // PUT /api/v1/usuarios/{id}
    // Editar usuario (CU-04)
    // ──────────────────────────────────────────────────────
    public function update(UpdateUsuarioRequest $request, int $id): JsonResponse
    {
        $usuario = Usuario::find($id);

        if (! $usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        // Construir solo los campos que vienen en el request
        $datos = array_filter([
            'id_rol'        => $request->id_rol,
            'txt_username'  => $request->txt_username,
            'txt_email'     => $request->txt_email,
            'bol_estado'    => $request->has('bol_estado') ? $request->bol_estado : null,
            'txt_password'  => $request->filled('txt_password')
                               ? Hash::make($request->txt_password)
                               : null,
        ], fn($v) => ! is_null($v));

        $datos['fch_actualizacion'] = now();

        $usuario->update($datos);

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente.',
            'data'    => $usuario->load('rol'),
        ], 200);
    }

    // ──────────────────────────────────────────────────────
    // DELETE /api/v1/usuarios/{id}
    // Eliminación lógica: pone bol_estado = false (CU-04)
    // No se elimina físicamente para preservar auditoría
    // ──────────────────────────────────────────────────────
    public function destroy(int $id): JsonResponse
    {
        $usuario = Usuario::find($id);

        if (! $usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        // Evitar que el admin se desactive a sí mismo
        if ($usuario->id_usuario === auth('api')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes desactivar tu propia cuenta.',
            ], 403);
        }

        $usuario->update([
            'bol_estado'        => false,
            'fch_actualizacion' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario desactivado correctamente.',
        ], 200);
    }

    // ──────────────────────────────────────────────────────
    // PATCH /api/v1/usuarios/{id}/estado
    // Activar o desactivar usuario (toggle)
    // ──────────────────────────────────────────────────────
    public function toggleEstado(int $id): JsonResponse
    {
        $usuario = Usuario::find($id);

        if (! $usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        if ($usuario->id_usuario === auth('api')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes modificar el estado de tu propia cuenta.',
            ], 403);
        }

        $nuevoEstado = ! $usuario->bol_estado;

        $usuario->update([
            'bol_estado'        => $nuevoEstado,
            'fch_actualizacion' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $nuevoEstado ? 'Usuario activado.' : 'Usuario desactivado.',
            'data'    => ['bol_estado' => $nuevoEstado],
        ], 200);
    }
}
