<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePayerRequest;
use App\Http\Requests\UpdatePayerRequest;
use App\Models\Payer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PayerController extends Controller
{
    public function index()
    {
        try {
            $payers = Payer::paginate(10);

            return response()->json([
                'status' => 'success',
                'data' => $payers,
                'pagination' => [
                    'total' => $payers->total(),
                    'count' => $payers->count(),
                    'per_page' => $payers->perPage(),
                    'current_page' => $payers->currentPage(),
                    'total_pages' => $payers->lastPage()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving data',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StorePayerRequest $request)
    {
        DB::beginTransaction();
        try {
            $payer = Payer::create($request->validated());

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $payer,
                'message' => 'Payer created successfully'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the payer',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $payer = Payer::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $payer
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payer not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the payer',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdatePayerRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $payer = Payer::findOrFail($id);
            $payer->update($request->validated());

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $payer,
                'message' => 'Payer updated successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Payer not found'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the payer',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $payer = Payer::findOrFail($id);
            $payer->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Payer deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Payer not found'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the payer',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
