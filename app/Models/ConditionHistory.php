<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConditionHistory extends Model
{
    protected $fillable = ['patient_id', 'condition', 'timestamp'];
}