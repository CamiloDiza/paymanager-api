<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class DistributionController extends Controller
{
    public function index()
    {
        try {
            $distributions = Distribution::paginate(10);

            return response()->json([
                'status' => 'success',
                'data' => $distributions,
                'pagination' => [
                    'total' => $distributions->total(),
                    'count' => $distributions->count(),
                    'per_page' => $distributions->perPage(),
                    'current_page' => $distributions->currentPage(),
                    'total_pages' => $distributions->lastPage()
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

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'income_id' => 'required|exists:incomes,id',
                'receiver_percentage' => 'required|numeric|min:0|max:100',
                'payer_percentage' => 'required|numeric|min:0|max:100',
                'receiver_amount_cop' => 'required|numeric|min:0',
                'payer_amount_cop' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $distribution = Distribution::create($request->all());
            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $distribution,
                'message' => 'Distribution created successfully'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the distribution',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $distribution = Distribution::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $distribution
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Distribution not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the distribution',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $distribution = Distribution::findOrFail($id);

            $rules = [
                'income_id' => 'sometimes|required|exists:incomes,id',
                'receiver_percentage' => 'sometimes|required|numeric|min:0|max:100',
                'payer_percentage' => 'sometimes|required|numeric|min:0|max:100',
                'receiver_amount_cop' => 'sometimes|required|numeric|min:0',
                'payer_amount_cop' => 'sometimes|required|numeric|min:0',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $distribution->update($request->only(array_keys($rules)));
            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $distribution,
                'message' => 'Distribution updated successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Distribution not found'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the distribution',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $distribution = Distribution::findOrFail($id);
            $distribution->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Distribution deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Distribution not found'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the distribution',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
