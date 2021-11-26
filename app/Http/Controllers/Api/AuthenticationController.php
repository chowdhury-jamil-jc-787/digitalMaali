<?php

namespace App\Http\Controllers\Api;

use App\Actions\ApiAction;
use App\Helpers\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GenerateOtpRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegistrationRequest;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class AuthenticationController extends Controller
{
  use ApiResponser;
  
  public function login(LoginRequest $request)
  {
    $user = User::where('phone', $request->phone)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
      abort(401, 'Login Failed');
    }

    $user->tokens()->delete();
    
    $data = [
      'token' => $user->createToken('Android-Access-Token')->plainTextToken,
      'user_data' => $user,
    ];

    return $this->successResponse($data, 'Logged In Successfully');
  }

  public function logout(Request $req)
  {
    auth()->user()->tokens()->delete(); 

    return $this->successResponse('','You have successfully logged out');
  }
  
  public function generateOtp(GenerateOtpRequest $request, ApiAction $apiAction) {
    
    $optCode = rand(1000, 9999);
    $appSignature = $request->app_signature_code;
    
    $otp = Otp::updateOrCreate(
      ['phone' => $request->phone, 'app_signature_code' => $request->app_signature_code],
      ['phone' => $request->phone, 'app_signature_code' => $appSignature, 'otp' => $optCode]
    );
    
    $responseArr= $otp->only(['phone', 'app_signature_code']);
    $responseArr['otp_message'] = "Your OTP for registration of skyflora: {$otp->otp} $otp->app_signature_code";
    
    $apiAction->sendOtp($optCode, $request);
    
    return $this->successResponse($responseArr, 'Requested for OPT');
  }
  
  public function registration(RegistrationRequest $request, ApiAction $apiAction) {
    $response = $apiAction->registerNewUser($request);
    
    if (!$response['success']) {
      return $this->errorResponse($response['msg'], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    
    return $this->successResponse($response['data'], $response['msg']);
  }
}
