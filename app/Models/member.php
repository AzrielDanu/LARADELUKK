<?php

namespace App\Models;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'point',
        
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'purchase_id');
    }
}
