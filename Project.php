<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table    = 'projects';
    protected $fillable = ['category_id', 'name', 'slug', 'date', 'picture', 'description', 'status'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function file()
    {
        return $this->hasMany(File::class, 'project_id');
    }
}
