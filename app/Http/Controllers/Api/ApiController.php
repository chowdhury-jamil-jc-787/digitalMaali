<?php

namespace App\Http\Controllers\Api;

use App\Actions\ApiAction;
use App\Helpers\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IssueCreateRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
  use ApiResponser;
  
    public function issueCreate(IssueCreateRequest $request, ApiAction $apiAction) {
      $response = $apiAction->createPlantIssue($request);
      
      if (!$response['success']) {
        return $this->errorResponse($response['msg'], Response::HTTP_UNPROCESSABLE_ENTITY);
      }
      
      return $this->successResponse($response['data'], $response['msg']);
    }
    
    public function issueList(ApiAction $apiAction) {
      $response = $apiAction->getIssueList();
      return $this->successResponse($response, 'Issue list received');
    }
}
