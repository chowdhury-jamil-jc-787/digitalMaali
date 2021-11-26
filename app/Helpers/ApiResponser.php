<?php

namespace App\Helpers;

use Illuminate\Http\Response;

trait ApiResponser
{
  public function successResponse($data, $msg, $code = Response::HTTP_OK)
  {
    return response()->json([
      'code' => $code,
      'message' => $msg,
      'data' => $data
    ], $code);
  }

  public function errorResponse($message, $code)
  {
    return response()->json([
      'code' => $code,
      'message' => $message,
    ], 200);
  }
}
