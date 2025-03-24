<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookGroup extends Model
{
    use HasFactory;
    protected $fillable = ['group_name'];

    // Relationship: A group can have many products
    public function products()
    {
        return $this->hasMany(Product::class, 'book_group_id');
    }
}
