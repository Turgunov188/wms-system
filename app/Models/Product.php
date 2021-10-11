<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ["code", "name", "unit_id", "delete_time"];

    public function units()
    {
        return $this->belongsTo(Unit::class, 'unit_id')->select(['id', 'name']);
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'product_materials')->withPivot(["quantity"])->select(["materials.id", "name", "unit_id"]);
    }
}
