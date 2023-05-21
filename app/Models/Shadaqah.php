<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shadaqah extends Model
{
    use HasFactory;
    protected $table = 'shadaqah';
    protected $primaryKey = 'id';
    //protected $dateFormat = 'U';
    protected $guarded = [];
}
