<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ConfirmOrderForm extends FormRequest
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

            'payment.method'    => ['required', 'string'],
            // 'payment.ltn'       => ['required_if:payment.method,==,' . CartPayment::METHOD_WHISH_TO_WHISH],
            // 'payment.receipt'   => ['mimes:bmp,jpeg,jpg,png,webp', 'required_if:payment.method,==,' . CartPayment::METHOD_WHISH_MONEY],
        ];

        return $this->rules;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'payment.method'    => 'Payment Method',
            'payment.ltn'       => 'LTN',
            'payment.receipt'   => 'Receipt',
        ];
    }
}
