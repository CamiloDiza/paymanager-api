<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class IncomeController extends Controller
{
    public function index()
    {
        try {
            $incomes = Income::paginate(10);

            return response()->json([
                'status' => 'success',
                'data' => $incomes,
                'pagination' => [
                    'total' => $incomes->total(),
                    'count' => $incomes->count(),
                    'per_page' => $incomes->perPage(),
                    'current_page' => $incomes->currentPage(),
                    'total_pages' => $incomes->lastPage()
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
                'receiver_id' => 'required|exists:receivers,id',
                'period_id' => 'required|exists:periods,id',
                'tokens' => 'required|integer|min:0',
                'total_usd' => 'required|numeric',
                'total_cop' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $income = Income::create($request->all());
            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $income,
                'message' => 'Income created successfully'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the income',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $income = Income::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $income
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Income not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the income',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $income = Income::findOrFail($id);

            $rules = [
                'receiver_id' => 'sometimes|required|exists:receivers,id',
                'period_id' => 'sometimes|required|exists:periods,id',
                'tokens' => 'sometimes|required|integer|min:0',
                'total_usd' => 'sometimes|required|numeric',
                'total_cop' => 'sometimes|required|numeric',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $income->update($request->only(array_keys($rules)));
            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $income,
                'message' => 'Income updated successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Income not found'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the income',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $income = Income::findOrFail($id);
            $income->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Income deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Income not found'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the income',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
