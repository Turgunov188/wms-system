<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {

            return [
                "data" => Material::with('units')
                    ->whereNull('delete_time')
                    ->get(),
                "success" => true];

        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {

            $data = $request->only(["name", "unit_id"]);

            $validator = Validator::make($data, [
                "name" => "required|unique:materials",
                "unit_id" => "required|exists:units,id"
            ]);
            if ($validator->fails()) {
                $returnData = [
                    "error" => $validator->errors()->all(),
                    "success" => false
                ];
                return response()->json($returnData, 400);
            }

            $product = Material::create($data);

            return ["success" => true, "error" => false];

        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Material $material
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        //
        try {
            return [
                "data" => $material,
                "success" => true
            ];
        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Material $material
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Material $material)
    {
        //
        try {

            $data = $request->only(["name", "unit_id"]);

            $validator = Validator::make($data, [
                "name" => "required",
                "unit_id" => "required|exists:units,id"
            ]);
            if ($validator->fails()) {
                $returnData = [
                    "error" => $validator->errors()->all(),
                    "success" => false
                ];
                return response()->json($returnData, 400);
            }

            $material->update($data);

            return ["success" => true, "error" => false, "data" => $material];

        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Material $material
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        //
        try {

            $material->delete_time = date('Y-m-d H:i:s');

            if ($material->save()) {
                return ["success" => true, "error" => false];
            }

        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }
}
