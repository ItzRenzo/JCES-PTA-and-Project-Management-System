<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'projectID';
    public $timestamps = false;

    protected $fillable = [
        'project_name',
        'description',
        'goals',
        'target_budget',
        'current_amount',
        'start_date',
        'target_completion_date',
        'actual_completion_date',
        'project_status',
        'created_by',
        'created_date',
        'updated_date',
    ];

    protected $casts = [
        'target_budget' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'start_date' => 'date',
        'target_completion_date' => 'date',
        'actual_completion_date' => 'date',
        'created_date' => 'datetime',
        'updated_date' => 'datetime',
    ];

    public function contributions()
    {
        return $this->hasMany(ProjectContribution::class, 'projectID', 'projectID');
    }

    public function updates()
    {
        return $this->hasMany(ProjectUpdate::class, 'projectID', 'projectID');
    }

    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'projectID', 'projectID');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'userID');
    }
}
