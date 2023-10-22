<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GiftPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      $canShow = auth()->user()->id == $this->sender_id ? 1 : 0 ;

        return [
            'id' => $this->id,
            'sender_id' => $this->sender_id,
            'sender' => $this->sender->getuserFullNameAttribute(),
            'receiver_id' => $this->receiver_id,
            'receiver' => $this->receiver->getuserFullNameAttribute(),
            'payment_method' =>  $this->payment_method ,
            'ltn_number' => $canShow == 1 ?  $this->ltn_number : null,
            'receipt' => $canShow == 1 ? $this->receipt ? url('storage/' . $this->receipt) : null : null,
            'status' => $this->status,
            'reason' => $this->reason,
            'i_am_the_sender' => $canShow ,
        ];
    }
}
