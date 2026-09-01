<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobWorkplace extends Model
{
    protected $fillable = [
        'job_id',
        'workplace_value',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
