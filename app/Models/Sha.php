<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sha extends Model
{
    use HasFactory;
    protected $table = 'sha';
    protected $primaryKey = 'id';
    protected $fillable = ['nama', 'harga'];
}
