<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseApiController extends Controller
{
    protected function success(mixed $data = null, string $message = 'Success', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function error(string $message = 'Error', int $code = 400, mixed $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }

    protected function paginated(mixed $resource, string $message = 'Success')
    {
        return response()->json([
            'success'    => true,
            'message'    => $message,
            'data'       => $resource->items(),
            'pagination' => [
                'total'        => $resource->total(),
                'per_page'     => $resource->perPage(),
                'current_page' => $resource->currentPage(),
                'last_page'    => $resource->lastPage(),
                'has_more'     => $resource->hasMorePages(),
                'next_page_url' => $resource->nextPageUrl(),
                'prev_page_url' => $resource->previousPageUrl(),
            ],
        ]);
    }
}
