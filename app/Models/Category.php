<?php

namespace App\Models;

use App\Contracts\NumerologyCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model implements NumerologyCategory
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year_formula',
    ];

    public function getYearFormula(): string
    {
        return $this->attributes['year_formula'];
    }
}
