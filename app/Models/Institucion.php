<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institucion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'instituciones';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'director',
    ];
}