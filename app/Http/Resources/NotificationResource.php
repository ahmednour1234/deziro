<?php

namespace App\Http\Resources;

use App\Http\Resources\Boost;
use App\Http\Resources\Cart;
use App\Http\Resources\Order;
use App\Http\Resources\Product;


use App\Models\SeeNotification;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        $notificationId = $this->id; // Assuming $this refers to the current instance of the Notification model
        $userId = auth()->user()->id; // Assuming you can access the authenticated user's ID from the token

        // Check if the record exists in the see_notifications table
        $existingRecord = SeeNotification::where('notification_id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        $seeValue = $existingRecord ? 1 : 0;

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'notifiable_type' => $this->notifiable_type,
            'notifiable_id' => $this->notifiable_id,
            'notifiable' => new Order($this->order),
            'action' => $this->action,
            'topic' => $this->topic,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'see' => $seeValue,
        ];
    }
}
