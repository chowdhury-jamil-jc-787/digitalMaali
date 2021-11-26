<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlantIssue;

class issueController extends Controller
{
    public function show(){

      $data =   PlantIssue::orderBy('id','desc')->paginate(5);
      return view('issues',compact('data'));
    }
    
}
