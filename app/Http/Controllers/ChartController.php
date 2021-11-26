<?php

namespace App\Http\Controllers;
use App\Models\PlantIssue;
use App\Models\User;

use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function show()
    {
        $totalUser = User::select('id')->count();
        $delivered = PlantIssue::where('status','=','1')->count();
        $pending = PlantIssue::where('status','=','0')->count();
        $totalProblem = $delivered+$pending;

        $percentDelivered = ($delivered*100)/$totalProblem;
        $percentPending = ($pending*100)/$totalProblem;
        $percentTotalProblem = ($totalProblem*100)/$totalUser;

        $intDelivered = (int)$percentDelivered;
        $intPending = (int)$percentPending;
        $intTotalProblem = (int)$percentTotalProblem;

        $data =   PlantIssue::orderBy('id','desc')->paginate(2);


       //return $totalUser;





        return view('chart',compact('data','totalProblem','totalUser','delivered','pending','intDelivered','intPending','intTotalProblem'));
    }
}
