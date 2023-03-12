<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return ['data'=>$this->collection];
    }

    public function with($request)
    {
        return ['status'=>'success'];
    }

    public function withResponse($request,$response)
    {
        $response->header('Accept','application/json');
    }
}
