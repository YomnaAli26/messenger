<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

trait ResponseTrait
{
    public function getResponse($code = 200,$message = "success",$data=[],$errors=[]): JsonResponse
    {
        $result = $code>=200 && $code<300 ? "success" : "error";
        $response = [
            'result' => $result,
            'code' => $code,
            'message' => $message,
            'timestamp' => now()->toDateString(),
        ];
        if (!empty($data))
        {
            $response['data'] = $data;
        }
        if (!empty($errors))
        {
            $response['errors'] = $errors;
        }

        return response()->json($response,$code);

    }

}
