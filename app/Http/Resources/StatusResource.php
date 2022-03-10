<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
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
    public function deffOnlineTime($start_time, $finish_time)
    {
        $start_time = new Carbon($start_time);
        $finish_time = new Carbon($finish_time);
        $diff_time = $start_time->diff($finish_time);
        return $diff_time->format("%h時間%i分");
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
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'imgPath' => $this->user->img_path,
                'vehicleModel' => $this->vehicleModel($this->user->vehicle_model),
            ],
            'summary' => [
                'startTime' => $this->created_at->format('Y/m/d H:i:s'),
                'endTime' => $this->finish_at->format('Y/m/d H:i:s'),
                'onlineTime' => $this->deffOnlineTime($this->created_at, $this->finish_at),
                'daysEarningsTotal' => $this->days_earnings_total,
                'actualCost' => $this->actual_cost,
                'daysEarningsQty' => $this->days_earnings_qty
            ],
            'chartData' => ChartDataResource::collection($this->chart_data)
        ];
    }
}
