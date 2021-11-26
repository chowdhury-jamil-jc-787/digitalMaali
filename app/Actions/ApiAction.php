<?php

namespace App\Actions;

use App\Constants\SystemConstant;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PlantIssue;
use Carbon\Carbon;

class ApiAction
{
  public function registerNewUser($request)
  {
    $otp = Otp::where('phone', $request->phone)->where('otp', $request->otp)->first();

    $response = array();

    if (!$otp) {
      $response['success'] = false;
      $response['msg'] = 'Registration failed due to OTP mismatch';
      return $response;
    }

    $user = new User();
    $user->name = $request->name;
    $user->phone = $request->phone;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->save();

    $otp->delete();

    $response['success'] = true;
    $response['msg'] = 'Account successfully created';
    $response['data'] = $user;

    return $response;
  }

  public function createPlantIssue($request)
  {
    $plantIssue = new PlantIssue();
    $plantIssue->user_id = Auth::user()->id;
    $plantIssue->title = $request->title;
    $plantIssue->answer1 = $request->answer1;
    $plantIssue->answer2 = $request->answer2;
    $plantIssue->problem = $request->problem;
    $plantIssue->image = 'image path';
    $plantIssue->status = SystemConstant::PLANT_ISSUE_STATUS['pending'];

    $fileName = time() . '.' . $request->image->extension();
    $request->image->move(public_path('plant-issues-img/'), $fileName);
    
    $plantIssue->image = $fileName;
    $plantIssue->save();

    return [
      'success' => true,
      'msg' => 'New issue created',
      'data' => $plantIssue,
    ];
  }
  
  public function getIssueList() {

    $issueList = PlantIssue::where('user_id', Auth::user()->id)->orderByDesc('id')->paginate(10);

    $list = $issueList->items();
    $issues = array();

    foreach ($list as $item) {
      $issues[] = [
        'issue_id' => $item->id,
        'issue_title' => $item->title,
        'plant_image' => asset('plant-issues-img/' . $item->image),
        'requested_date' => $item->created_at,
        'issue_detail' => $item->problem,
        'question_set' => [
          ['question' => 'আপনার বাগানের ধরন কী?', 'answer' => $item->answer1],
          ['question' => 'গাছের প্রজাতি কী?', 'answer' => $item->answer2],
        ],
        'issue_responded' => [
          'status' => ($item->status == 1) ? 'Responded' : 'Pending',
          'issue_response' => ($item->status == 1) ? optional($item->solution)->solution : '',
          'response_date' => ($item->status == 1) ? optional($item->solution)->created_at : ''
        ]
      ];
    }

    $response = [
      'has_next_page' => $issueList->nextPageUrl() ? true : false,
      'issue_list' => $issues
    ];
    
    return $response;
  }
  
  public function sendOtp($otp, $request) {
    $appSignature = $request->app_signature_code;
    
    $url = "http://66.45.237.70/api.php";
    $number = $request->phone;
    $text = "Your OTP for registration of skyflora is: {$otp} . OTP will be expired in 2 mins. Do not share it with anyone. $appSignature";
    $data = array(
      'username' => "01675359644",
      'password' => "Aqualink@321",
      'number' => "$number",
      'message' => "$text"
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $smsresult = curl_exec($ch);
    $p = explode("|", $smsresult);
    $sendstatus = $p[0];
  }
}
