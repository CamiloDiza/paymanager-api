<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReceiverRequest;
use App\Http\Requests\UpdateReceiverRequest;
use App\Models\Receiver;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ReceiverController extends Controller
{
    // Método para obtener una lista paginada de receptores
    public function index()
    {
        try {
            // Obtiene los receptores con paginación de 10 por página
            $receivers = Receiver::paginate(10);

            // Retorna una respuesta JSON con los datos de los receptores y la información de paginación
            return response()->json([
                'status' => 'success',
                'data' => $receivers,
                'pagination' => [
                    'total' => $receivers->total(),
                    'count' => $receivers->count(),
                    'per_page' => $receivers->perPage(),
                    'current_page' => $receivers->currentPage(),
                    'total_pages' => $receivers->lastPage()
                ]
            ], 200);
        } catch (\Exception $e) {
            // Retorna una respuesta JSON de error si ocurre una excepción
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving data',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // Método para almacenar un nuevo receptor
    public function store(StoreReceiverRequest $request)
    {
        // Inicia una transacción de base de datos
        DB::beginTransaction();
        try {
            // Crea un nuevo receptor con los datos validados del request
            $receiver = Receiver::create($request->validated());

            // Confirma la transacción
            DB::commit();
            // Retorna una respuesta JSON de éxito
            return response()->json([
                'status' => 'success',
                'data' => $receiver,
                'message' => 'Receiver created successfully'
            ], 201);
        } catch (\Exception $e) {
            // Revierte la transacción en caso de error
            DB::rollBack();
            // Retorna una respuesta JSON de error
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the receiver',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // Método para mostrar un receptor específico
    public function show($id)
    {
        try {
            // Busca el receptor por ID, lanza una excepción si no se encuentra
            $receiver = Receiver::findOrFail($id);
            // Retorna una respuesta JSON de éxito con los datos del receptor
            return response()->json([
                'status' => 'success',
                'data' => $receiver
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Retorna una respuesta JSON de error si no se encuentra el receptor
            return response()->json([
                'status' => 'error',
                'message' => 'Receiver not found'
            ], 404);
        } catch (\Exception $e) {
            // Retorna una respuesta JSON de error en caso de otra excepción
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the receiver',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // Método para actualizar un receptor existente
    public function update(UpdateReceiverRequest $request, $id)
    {
        // Inicia una transacción de base de datos
        DB::beginTransaction();
        try {
            // Busca el receptor por ID, lanza una excepción si no se encuentra
            $receiver = Receiver::findOrFail($id);
            // Actualiza el receptor con los datos validados del request
            $receiver->update($request->validated());

            // Confirma la transacción
            DB::commit();
            // Retorna una respuesta JSON de éxito
            return response()->json([
                'status' => 'success',
                'data' => $receiver,
                'message' => 'Receiver updated successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Revierte la transacción en caso de error
            DB::rollBack();
            // Retorna una respuesta JSON de error si no se encuentra el receptor
            return response()->json([
                'status' => 'error',
                'message' => 'Receiver not found'
            ], 404);
        } catch (\Exception $e) {
            // Revierte la transacción en caso de otra excepción
            DB::rollBack();
            // Retorna una respuesta JSON de error en caso de otra excepción
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the receiver',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // Método para eliminar un receptor
    public function destroy($id)
    {
        // Inicia una transacción de base de datos
        DB::beginTransaction();
        try {
            // Busca el receptor por ID, lanza una excepción si no se encuentra
            $receiver = Receiver::findOrFail($id);
            // Elimina el receptor
            $receiver->delete();

            // Confirma la transacción
            DB::commit();
            // Retorna una respuesta JSON de éxito
            return response()->json([
                'status' => 'success',
                'message' => 'Receiver deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Revierte la transacción en caso de error
            DB::rollBack();
            // Retorna una respuesta JSON de error si no se encuentra el receptor
            return response()->json([
                'status' => 'error',
                'message' => 'Receiver not found'
            ], 404);
        } catch (\Exception $e) {
            // Revierte la transacción en caso de otra excepción
            DB::rollBack();
            // Retorna una respuesta JSON de error en caso de otra excepción
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the receiver',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
