<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['material_id', 'remainder', 'price', 'delete_time'];

    public function materials()
    {
        return $this->belongsTo(Material::class, 'material_id')->select(['id', 'name', 'unit_id']);
    }
}
