<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;



trait ApiResponse
{
    // =========================> for success endpoint <=======================
    public static function success($data = [],$message = 'Operation successful', $code = 200): JsonResponse {
        $response = [
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ];

        return response()->json($response, $code);
    }

    
    // =========================> for endpoint with error <=======================
    public static function error($message = 'Something went wrong',$code = 400, $errors = null): JsonResponse {
        $response = [
            'status'  => 'error',
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

}








