<?php

namespace App\Models;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_purchase extends Model
{
    use HasFactory;

    protected $table = 'detail_purchase'; 
    
    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'sub_total'
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
