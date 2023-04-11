<?php

namespace App\Http\Requests;

use App\Models\CartPayment;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutForm extends FormRequest
{
    /**
     * Rules.
     *
     * @var array
     */
    protected $rules;

    /**
     * Determine if the checkout is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $this->rules = [
            'items.*'           => ['required'],

            'address.city'      => ['required_without:address_id', 'string'],
            'address.address'   => ['required_without:address_id', 'string'],
            'address.lat'       => ['required_without:address_id'],
            'address.lng'       => ['required_without:address_id'],

            // 'payment.method'    => ['required', 'string'],
            // 'payment.ltn'       => ['required_if:payment.method,==,' . CartPayment::METHOD_WHISH_TO_WHISH],
            // 'payment.receipt'   => ['mimes:bmp,jpeg,jpg,png,webp', 'required_if:payment.method,==,' . CartPayment::METHOD_WHISH_MONEY],
        ];

        return $this->rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'address.city'      => 'City',
            'address.address'   => 'Address',
            'address.lat'       => 'Lat',
            'address.lng'       => 'Lng',
            'payment.method'    => 'Payment Method',
            'payment.ltn'       => 'LTN',
            'payment.receipt'   => 'Receipt',
        ];
    }
}
