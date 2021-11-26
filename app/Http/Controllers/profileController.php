<?php

namespace App\Http\Controllers;
use App\Models\PlantIssue;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class profileController extends Controller
{
    public function show(Request $request, $id)
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


        $id = $request->id;
       $name = User::where('id', $id)->value('name');
       $email = User::where('id', $id)->value('email');
      

        return view('profile',compact('name','email','data','totalProblem','totalUser','delivered','pending','intDelivered','intPending','intTotalProblem'));
    }

    public function update(Request $request)
    {
        $update = new User;
        $update->password = $request->pass;
        
        //return $update->name;
        dd($update->password);
    }








}
