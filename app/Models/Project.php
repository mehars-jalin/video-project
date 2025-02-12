<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VideoTopic;
use App\Models\Company;


class Project extends Model {
    protected $fillable = ['company_id',
     'project_name',
      'project_type',
       'created_date',
        'videos_allotted',
         'videos_completed',
          'project_notes',
           'status',
            'due_date', 
            'video_topics', 
            'months_between_shoots', 
            'last_shoot_date' ];

    protected $casts = [
        'video_topics' => 'array', // To store the video topics as an array
        'created_date' => 'date',
        'due_date' => 'date',
        'last_shoot_date' => 'date',
    ];
    public function company() 
    { 
        return $this->belongsTo(Company::class); 
    } 
    public function videoTopics()
    {
        return $this->hasMany(VideoTopic::class);
    }
    
    public function getNextShootDate()
    {
        if ($this->project_type === 'recurring' && $this->last_shoot_date) {
            return \Carbon\Carbon::parse($this->last_shoot_date)
                ->addMonths($this->months_between_shoots);
        }
        return null;
    }

    public function getNextShootDateAttribute()
    {
        if ($this->project_type === 'recurring' && $this->last_shoot_date && $this->months_between_shoots) {
            return \Carbon\Carbon::parse($this->last_shoot_date)->addMonths($this->months_between_shoots);
        }
        return null;
    }

    public function getNextShootStatusAttribute()
    {
        if ($this->project_type === 'recurring' && $this->next_shoot_date) {
            $today = now();
            $nextShoot = $this->next_shoot_date;

            if ($nextShoot < $today) {
                return 'red'; // Overdue
            } elseif ($nextShoot->diffInDays($today) <= 30) {
                return 'yellow'; // Within 30 days
            }
        }
        return 'green'; // Otherwise
    }


    
}
