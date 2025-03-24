<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
     public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }
    public function supllier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(Orderdetails::class, 'product_id');
    }
    public function group()
    {
        return $this->belongsTo(BookGroup::class, 'book_group_id');
    }
    
}
