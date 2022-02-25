<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function vehicleModel($model_id)
    {
        if ($model_id == 0) {
            return '車';
        } elseif ($model_id == 1) {
            return 'バイク';
        } elseif ($model_id == 2) {
            return '自転車';
        } else {
            return '徒歩';
        }
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'vehicle_model' => $this->vehicleModel($this->vehicle_model),
            'access_token' => $this->access_token
        ];
    }
}
