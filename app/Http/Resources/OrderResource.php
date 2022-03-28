<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id' => $this->id,
            'prefecture_id' => $this->prefecture_id,
            'earnings_incentive' => $this->earnings_incentive,
            'earnings_base' => $this->earnings_base,
            'earnings_total' => $this->earnings_total,
            'order_received_at' => $this->order_received_at,
        ];
    }
}
