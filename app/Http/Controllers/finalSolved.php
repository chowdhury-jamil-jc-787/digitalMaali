<?php

namespace App\Http\Controllers;

use App\Models\Solution;
use App\Models\PlantIssue;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class finalSolved extends Controller
{
  public function solution(Request $request, $issueId)
  {
    $issue = PlantIssue::findOrFail($issueId);
    
    $solution = new Solution();
    $solution->user_id = $issue->user_id;
    $solution->plant_issue_id = $issue->id;
    $solution->solution = $request->textArea;
    $solution->save();
    
    $issue->status = 1;
    $issue->save();

    return redirect()->route('issuelist');
  }
}
