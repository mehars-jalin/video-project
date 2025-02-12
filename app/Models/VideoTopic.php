<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Project;

class VideoTopic extends Model
{
    //
    use HasFactory, Notifiable;

    protected $fillable = ['project_id','topic'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
