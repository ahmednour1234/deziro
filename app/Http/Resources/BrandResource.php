<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $boost_ends_in = '';
        // if ($this->boosted_at && $this->boost > 0) {
        //     $startDate = Carbon::now();
        //     $endDate = Carbon::parse($this->boosted_at)->addDays($this->boost);
        //     $days = $startDate->diffInDays($endDate);
        //     $hours = $startDate->copy()->addDays($days)->diffInHours($endDate);
        //     $minutes = $startDate->copy()->addDays($days)->addHours($hours)->diffInMinutes($endDate);
        //     $boost_ends_in = $days . 'd ' .  $hours . 'h ' .  $minutes . 'm';
        // }

        // $exchange_rate = $this->user->isStore() &&  $this->user->vendor_exchange_rate > 0 ? $this->user->vendor_exchange_rate : 60000;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => url('storage/' . $this->image_path),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
