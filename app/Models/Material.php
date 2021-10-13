<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = ["name", "unit_id", "delete_time"];

    public function units()
    {
        return $this->belongsTo(Unit::class, 'unit_id')->select(['id', 'name']);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class)->select(["id", "material_id", "remainder", "price"])->orderBy('id');
    }
}
