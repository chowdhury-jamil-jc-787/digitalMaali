<?php

namespace App\Models;

use App\Models\Solution;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantIssue extends Model
{
    use HasFactory;
    
    public function solution() {
      return $this->hasOne(Solution::class);
    }
}
