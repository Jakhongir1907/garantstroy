<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReturnResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'code' => $this->resource['code'] ?? null,
            'message' => $this->resource['message'] ?? null,
        ];
    }
    /**
     * Convert the resource to a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
       return response()->json(['data' => $this->toArray($request)] , $this->resource['code']);
    }
}
