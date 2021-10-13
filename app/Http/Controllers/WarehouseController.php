<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
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
                "data" => Material::with(["warehouses", "units"])
                    ->get(),
                "success" => true
            ];
        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }

    public function production(Request $request)
    {
        try {

            $products = $request->products;

            $productions = [];
            $used_material = [];
            foreach ($products as $product) {
                $query = Product::where("id", $product["product_id"])
                    ->with("materials", "materials.warehouses", "materials.units")
                    ->select("id", "code", "name")
                    ->first();
                $temp_material = [];
                foreach ($query->materials as $material) {
                    $material_item = [
                        "material_name" => $material["name"],
                        //"material_id" => $material["id"]
                    ];

                    $amount = 0;
                    $all_needed = $product['amount'] * $material['pivot']['quantity'];//24
                    foreach ($material->warehouses as $warehouse) {
                        if (
                            (isset($used_material[$warehouse['id']]) && $used_material[$warehouse['id']]['amount'] < $warehouse['remainder']) ||
                            !isset($used_material[$warehouse['id']])
                            && $amount < $all_needed
                        ){
                            $used_ws_amount = isset($used_material[$warehouse['id']]) ? $used_material[$warehouse['id']]['amount'] : 0;
                            $warehouse_item = [
                                'warehouse_id' => $warehouse['id'],
                                'qty' => $amount + $warehouse['remainder'] - $used_ws_amount > $all_needed ? $all_needed - $amount : $warehouse["remainder"] - $used_ws_amount,
                                'price' => $warehouse['price']
                            ];
                            $temp_material[] = array_merge($material_item, $warehouse_item);

                            if ($amount + $warehouse['remainder']  - $used_ws_amount > $all_needed){
                                if (isset($used_material[$warehouse['id']])){
                                    $used_material[$warehouse['id']]['amount'] += $all_needed - $amount;
                                }else{
                                    $used_material[$warehouse['id']] = [
                                        'amount' => $all_needed - $amount
                                    ];
                                }
                                $amount += $all_needed - $amount;
                            }else{
                                if (isset($used_material[$warehouse['id']])){
                                    $used_material[$warehouse['id']]['amount'] += $warehouse['remainder'] - $used_ws_amount;
                                }else{
                                    $used_material[$warehouse['id']] = [
                                        'amount' => $warehouse['remainder']
                                    ];
                                }
                                $amount += $warehouse['remainder'] - $used_ws_amount;
                            }
                        }

                    }
                    if ($amount < $all_needed) {
                        $material_missing = array_merge($material_item, [
                            'warehouse_id' => null,
                            'price' => null,
                            'qty' => $all_needed - $amount
                        ]);
                        $temp_material[] = $material_missing;
                    }
                }


                $productions[] = [
                    "id" => $query->id,
                    "product_name" => $query->name,
                    "product_code" => $query->code,
                    "product_qty" => $product["amount"],
                    "product_materials" => $temp_material,
                ];
            }

            return ["data" => $productions, "success" => true];
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
            $data = $request->only(["material_id", "remainder", "price"]);
            $validator = Validator::make($data, [
                "material_id" => "required|exists:materials,id",
                "remainder" => "required",
                "price" => "required"
            ]);
            if ($validator->fails()) {
                $returnData = [
                    "error" => $validator->errors()->all(),
                    "success" => false
                ];
                return response()->json($returnData, 400);
            }

            $warehouse = Warehouse::create($data);

            return ["success" => true, "error" => false];

        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Warehouse $warehouse
     * @return \Illuminate\Http\Response
     */
    public function show(Warehouse $warehouse)
    {
        //
        try {
            return ["data" => $warehouse, "success" => true];
        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Warehouse $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        //
        try {
            $data = $request->only(["material_id", "remainder", "price"]);
            $validator = Validator::make($data, [
                "material_id" => "required|exists:materials,id",
                "remainder" => "required",
                "price" => "required"
            ]);
            if ($validator->fails()) {
                $returnData = [
                    "error" => $validator->errors()->all(),
                    "success" => false
                ];
                return response()->json($returnData, 400);
            }

            $warehouse->update($data);

            return ["success" => true, "error" => false];

        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Warehouse $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warehouse $warehouse)
    {
        //
        try {
            $warehouse->delete_time = date('Y-m-d H:i:s');

            if ($warehouse->save()) {
                return ["success" => true, "error" => false];
            }

        } catch (\Exception $exception) {
            return ["error" => $exception->getMessage(), "success" => false];
        }
    }
}
