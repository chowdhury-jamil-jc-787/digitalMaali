<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlantIssue;
use App\Models\Solution;

class solvedController extends Controller
{
  public function solve(Request $request, $id)
  {
    $id = $request->id;
    $issue = PlantIssue::findOrFail($id);

    $sol = Solution::where('plant_issue_id', $id)->value('solution');
    

    $answer1 = $issue->answer1;
    $answer2 = $issue->answer2;
    $img = asset('plant-issues-img/' . $issue->image);
    $answer4 = $issue->problem;
    $answer5 = $issue->status;

    

    
    //return view('solved',compact('answer1'));

    //return view('solved')->with('answer1',$answer1);

    return view('solved', compact(['answer1', 'answer2', 'id', 'answer4', 'answer5', 'img', 'sol']));
  }
}
