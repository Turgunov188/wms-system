<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

    public $timestamps = false;

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public static function initialUnits(): array
    {
        return [
            [
                "name" => "шт"
            ],
            [
                "name" => "м"
            ],
            [
                "name" => "м2"
            ],
            [
                "name" => "м3"
            ],
            [
                "name" => "кг"
            ],
            [
                "name" => "гр"
            ],
        ];
    }
}
