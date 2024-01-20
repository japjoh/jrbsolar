<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table    = 'files';
    protected $fillable = ['project_id', 'file','for','status'];

}
