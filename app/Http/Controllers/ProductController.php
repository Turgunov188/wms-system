<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
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
                "data" => Product::with('units')
                    ->whereNull('delete_time')
                    ->get(),
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

            $data = $request->only(["code", "name", "unit_id"]);

            $validator = Validator::make($data, [
                "code" => "required|unique:products",
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

            $product = Product::create($data);

            return ["success" => true, "error" => false];

        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
        try {
            return [
                "data" => $product,
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
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
        try {

            $data = $request->only(["code", "name", "unit_id"]);

            $validator = Validator::make($data, [
                "code" => "required",
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

            $product->update($data);

            return ["success" => true, "error" => false, "data" => $product];

        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
        try {

            $product->delete_time = date('Y-m-d H:i:s');

            if ($product->save()) {
                return ["success" => true, "error" => false];
            }

        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }
}
