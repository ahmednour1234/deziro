<?php

namespace App\Repositories;

use Illuminate\Container\Container;
use App\Repositories\AttributeOptionRepository;
use App\Eloquent\Repository;
use App\Models\ProductAttributeValue;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AttributeRepository extends Repository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'code' => 'like',
        'name' => 'like',
        'type' => 'like',
        'created_at' => 'like',
        'options.name' => 'like'
    ];
    /**
     * Create a new repository instance.
     *
     * @param  \App\Repositories\AttributeOptionRepository  $attributeOptionRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected AttributeOptionRepository $attributeOptionRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'App\Models\Attribute';
    }

    /**
     * Create attribute.
     *
     * @param  array  $data
     * @return \App\Models\Attribute
     */
    public function create(array $data)
    {
        $data = $this->validateUserInput($data);

        $options = isset($data['options']) ? $data['options'] : [];

        unset($data['options']);

        $attribute = $this->model->create($data);

        if (
            in_array($attribute->type, ['select', 'multiselect', 'checkbox'])
            && count($options)
        ) {
            foreach ($options as $optionInputs) {
                $this->attributeOptionRepository->create(array_merge([
                    'attribute_id' => $attribute->id,
                    'swatch_type' => $attribute->swatch_type,
                ], $optionInputs));
            }
        }

        return $attribute;
    }

    /**
     * Update attribute.
     *
     * @param  array  $data
     * @param  int $id
     * @param  string  $attribute
     * @return \App\Models\Attribute
     */
    public function update(array $data, $id, $attribute = "id")
    {

        $data = $this->validateUserInput($data);

        $attribute = $this->find($id);

        $attribute->update($data);

        $previousOptionIds = $attribute->options()->pluck('id');

        if (in_array($attribute->type, ['select', 'multiselect', 'checkbox'])) {
            if (isset($data['options'])) {
                foreach ($data['options'] as $optionId => $optionInputs) {

                    if (Str::contains($optionId, 'option_')) {
                        $this->attributeOptionRepository->create(array_merge([
                            'attribute_id' => $attribute->id,
                            'swatch_type' => $attribute->swatch_type,
                        ], $optionInputs));
                    } else {
                        $optionId = $optionInputs['id'];

                        if (is_numeric($index = $previousOptionIds->search($optionId))) {
                            $previousOptionIds->forget($index);
                        }

                        $this->attributeOptionRepository->update(array_merge([
                            'swatch_type' => $attribute->swatch_type,
                        ], $optionInputs), $optionId);
                    }
                }
            }
        }

        foreach ($previousOptionIds as $previousOptionId) {
            $productAttributeValue = ProductAttributeValue::join('attributes', 'attributes.id', 'product_attribute_values.attribute_id')
                ->join('attribute_options', 'attributes.id', 'attribute_options.attribute_id')
                ->where('attributes.type', 'select')
                ->where('integer_value', $previousOptionId)->first();
            if (!$productAttributeValue)
                $this->attributeOptionRepository->delete($previousOptionId);
            else {
                throw new Exception('Error! Attribute option: ' . $productAttributeValue->name . ' cannot be deleted.');
            }
        }

        return $attribute;
    }

    /**
     * Validate user input.
     *
     * @param  array  $data
     * @return array
     */
    public function validateUserInput($data)
    {
        if (!isset($data['is_required']))
            $data['is_required'] = 0;
        if (!isset($data['is_unique']))
            $data['is_unique'] = 0;
        if (!isset($data['value_per_user']))
            $data['value_per_user'] = 0;
        if (!isset($data['is_filterable']))
            $data['is_filterable'] = 0;
        if (!isset($data['is_configurable']))
            $data['is_configurable'] = 0;
        if (!isset($data['is_visible_on_front']))
            $data['is_visible_on_front'] = 0;

        // if ($data['is_configurable']) {
        //     $data['value_per_user'] = $data['value_per_locale'] = 0;
        // }

        if (!in_array($data['type'], ['select', 'multiselect', 'price', 'checkbox'])) {
            $data['is_filterable'] = 0;
        }

        if (in_array($data['type'], ['select', 'multiselect', 'boolean'])) {
            unset($data['value_per_locale']);
        }

        return $data;
    }

    /**
     * Get filter attributes.
     *
     * @return array
     */
    public function getFilterAttributes()
    {
        return $this->model->with(['options'])->where('is_filterable', 1)->get();
    }

    /**
     * Get filter attributes.
     *
     * @return array
     */
    public function getUserFilterAttributes($category_id, $user_id)
    {
        return $this->model->with(['options' => function ($query) use ($user_id) {
            return $query->where('user_id', $user_id);
        }])->join('category_filterable_attributes', 'category_filterable_attributes.attribute_id', 'attributes.id')
            ->where('category_filterable_attributes.category_id', $category_id)
            ->where('is_filterable', 1)
            ->get();
    }

    /**
     * Get product default attributes.
     *
     * @param  array  $codes
     * @return array
     */
    public function getProductDefaultAttributes($codes = null)
    {
        $attributeColumns  = ['id', 'code', 'value_per_user', 'value_per_locale', 'type', 'is_filterable'];

        if (
            !is_array($codes)
            && !$codes
        )
            return $this->findWhereIn('code', [
                'name',
                'description',
                'short_description',
                'url_key',
                'price',
                'special_price',
                'special_price_from',
                'special_price_to',
                'status',
            ], $attributeColumns);

        if (in_array('*', $codes)) {
            return $this->all($attributeColumns);
        }

        return $this->findWhereIn('code', $codes, $attributeColumns);
    }

    /**
     * Get attribute by code.
     *
     * @param  string  $code
     * @return \App\Models\Attribute
     */
    public function getAttributeByCode($code)
    {
        static $attributes = [];

        if (array_key_exists($code, $attributes)) {
            return $attributes[$code];
        }

        return $attributes[$code] = $this->findOneByField('code', $code);
    }

    /**
     * Get attribute by id.
     *
     * @param  integer  $id
     * @return \App\Models\Attribute
     */
    public function getAttributeById($id)
    {
        static $attributes = [];

        if (array_key_exists($id, $attributes)) {
            return $attributes[$id];
        }

        return $attributes[$id] = $this->find($id);
    }

    /**
     * Get family attributes.
     *
     * @param  \App\Models\Product  $product
     * @return \App\Models\Attribute
     */
    public function getProductAttributes($product, $skipAttributes = [])
    {
        static $attributes = [];

        // if (array_key_exists($product->id, $attributes)) {
        //     return $attributes[$product->id];
        // }
        return $attributes[$product->id] = $product->attributes()->whereNotIn(
            'attributes.code',
            $skipAttributes
        )->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Get partials.
     *
     * @return array
     */
    public function getPartial()
    {
        $attributes = $this->model->all();

        $trimmed = [];

        foreach ($attributes as $key => $attribute) {
            if (
                $attribute->code != 'tax_category_id'
                && (in_array($attribute->type, ['select', 'multiselect'])
                )
            ) {
                array_push($trimmed, [
                    'id'      => $attribute->id,
                    'name'    => $attribute->name,
                    'type'    => $attribute->type,
                    'code'    => $attribute->code,
                    'options' => $attribute->options,
                ]);
            }
        }

        return $trimmed;
    }
}
