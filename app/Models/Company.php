<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model {
    protected $fillable = [ 'name', 'contact_name', 'contact_phone', 'contact_email', 'notes' ];
    public function projects()
    { 
        return $this->hasMany(Project::class); 
    } 
}
