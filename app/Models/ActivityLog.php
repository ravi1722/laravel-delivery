<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['model_type','model_id','action','old_values','new_values','performed_by','ip_address'];
}
