<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use App\Contracts\Validations\Decimal;
use App\Contracts\Validations\Slug;
use App\Models\ProductAttributeValue;
use App\Repositories\ProductAttributeValueRepository;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class ProductUpdateForm extends FormRequest
{
    /**
     * Rules.
     *
     * @var array
     */
    protected $rules;

    /**
     * Create a new form request instance.
     *
     * @param  \App\Repositories\ProductRepository  $productRepository
     * @param  \App\Repositories\ProductAttributeValueRepository  $productAttributeValueRepository
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductAttributeValueRepository $productAttributeValueRepository
    ) {
    }

    /**
     * Determine if the product is authorized to make this request.
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
        $this->rules = array_merge($this->getTypeValidationRules(), [
            'name'               => ['required', 'string'],
            'type'               => ['required', 'in:sell,bid,swap'],
            'product_type'       => ['required', 'in:simple,configurable'],
            'images.*'           => ['nullable', 'mimes:bmp,jpeg,jpg,png,webp'],
            'price'              => ['required', new Decimal],
            'special_price'      => ['nullable', new Decimal, 'exclude_if:price,0', 'lt:price'],
        ]);

        if (request()->images) {
            foreach (request()->images as $key => $file) {
                if ($file instanceof UploadedFile) {
                    $this->rules = array_merge($this->rules, [
                        'images.' . $key => ['required', 'mimes:bmp,jpeg,jpg,png,webp'],
                    ]);
                } else {
                    $this->rules = array_merge($this->rules, [
                        'images.' . $key => ['required', 'integer', 'exists:product_images,id'],
                    ]);
                }
            }
        }

        // if (auth()->user()->type == 1 && in_array(request()->type, ['sell', 'bed'])) {
        //     $this->rules = array_merge($this->rules, [
        //         'money_collection'   => ['required', 'in:whish-to-whish,whish-money'],
        //     ]);
        // }

        if (request()->type == 'bid') {
            $this->rules = array_merge($this->rules, [
                'countdown' => ['required', 'date_format:Y-m-d H:i:s', 'after:' . date('Y-m-d H:i:s')],
            ]);
        }

        if (!request()->brand_id && request()->brand_name) {
            $this->rules = array_merge($this->rules, [
                'brand_name' => ['required', 'string', 'min:2', Rule::unique('brands', 'name')],
            ]);
        }

        foreach (request()->attributes as $attribute) {
            if (
                $attribute->type == 'boolean'
            ) {
                continue;
            }

            $validations = [];

            if (!isset($this->rules[$attribute->code])) {
                array_push($validations, $attribute->is_required ? 'required' : 'nullable');
            } else {
                $validations = $this->rules[$attribute->code];
            }

            if (
                $attribute->type == 'text'
                && $attribute->validation
            ) {
                array_push(
                    $validations,
                    $attribute->validation == 'decimal'
                        ? new Decimal
                        : $attribute->validation
                );
            }

            if ($attribute->type == 'price') {
                array_push($validations, new Decimal);
            }

            if ($attribute->is_unique) {
                array_push($validations, function ($field, $value, $fail) use ($attribute) {
                    $column = ProductAttributeValue::$attributeTypeFields[$attribute->type];

                    if (!$this->productAttributeValueRepository->isValueUnique($this->id, $attribute->id, $column, request($attribute->code))) {
                        $fail(__('The :attribute has already been taken.'));
                    }
                });
            }

            $this->rules[$attribute->code] = $validations;
        }

        return $this->rules;
    }

    /**
     * Return validation rules.
     *
     * @return array
     */
    public function getTypeValidationRules()
    {
        if ($this->product_type == 'configurable')
            return [
                'variants.*.name'   => 'required',
                'variants.*.price'  => 'required',
            ];
        else
            return [];
    }

    /**
     * Custom message for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * Attributes.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'images.*' => 'image',
        ];
    }
}
