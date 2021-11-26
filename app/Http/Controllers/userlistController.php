<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class userlistController extends Controller
{
    public function show()
    {
      $data =   User::orderBy('id','desc')->paginate(5);
      return view('user',compact('data'));
    }
}
