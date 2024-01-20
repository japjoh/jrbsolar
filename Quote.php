<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $table    = 'quotes';
    protected $fillable = ['category_id','name', 'email','phone', 'address', 'contactMode','description','status','isRead'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
