<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        //
        try {
            return [
                "data" => Product::with("materials")->get(),
                "success" => true
            ];
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
            $data = $request->only("product_id", "material_id", "quantity");

            $validator = Validator::make($data, [
                "product_id" => "required|exists:products,id",
                "material_id" => "required|exists:materials,id",
                "quantity" => "required"
            ]);
            if ($validator->fails()) {
                $returnData = [
                    "error" => $validator->errors()->all(),
                    "success" => false
                ];
                return response()->json($returnData, 400);
            }

            $norma = ProductMaterial::create($data);

            return ["success" => true, "error" => false];

        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ProductMaterial $productMaterial
     * @return \Illuminate\Http\Response
     */
    public function show(ProductMaterial $productMaterial)
    {
        //
        try {
            return ["data" => ProductMaterial::find($productMaterial), "success" => true];
        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ProductMaterial $productMaterial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductMaterial $productMaterial)
    {
        //
        try {
            $data = $request->only("product_id", "material_id", "quantity");

            $validator = Validator::make($data, [
                "product_id" => "required|exists:products,id",
                "material_id" => "required|exists:materials,id",
                "quantity" => "required"
            ]);
            if ($validator->fails()) {
                $returnData = [
                    "error" => $validator->errors()->all(),
                    "success" => false
                ];
                return response()->json($returnData, 400);
            }

            $productMaterial->update($data);

            return ["success" => true, "error" => false];

        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ProductMaterial $productMaterial
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductMaterial $productMaterial)
    {
        //
        try {
            $productMaterial->delete_time = date('Y-m-d H:i:s');
            if ($productMaterial->save()) {
                return ["success" => true, "error" => false];
            }

        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }
}
