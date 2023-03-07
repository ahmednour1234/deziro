<?php

namespace App\Models;

use App\Facades\Cart;
use App\Http\Resources\Attribute as AttributeResource;
use App\Http\Resources\AttributeOption as AttributeOptionResource;
use App\Http\Resources\ProductAttribute;
use App\Repositories\AttributeRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'type',
        'product_type',
        'parent_id',
        'address_id',
        'quantity',
        'condition',
        'price',
        'special_price',
        'description',
        'category_id',
        'phone',
        'status',
    ];

    protected $skipAttributes = [
        'name',
        'user_id',
        'type',
        'product_type',
        'parent_id',
        'address_id',
        'quantity',
        'condition',
        'price',
        'special_price',
        'description',
        'category_id',
        'phone',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [];

    protected $minPrice = null;

    /**
     * Loaded attribute values.
     *
     * @var array
     */
    public static $loadedAttributeValues = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('inactive', function (Builder $builder) {
            $builder->where('products.status', '<>', 'inactive');
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function productimage()
    {
        return $this->hasMany(ImagesProduct::class, 'product_id', 'id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'id');
    }

    /**
     * The images that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id')
            ->orderBy('sort');
    }

    /**
     * Get the product that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * Return total quantity.
     *
     * @return int
     */
    public function totalQuantity()
    {
        $total = 0;

        if ($this->product_type == 'simple') {
            $total = $this->quantity;
        } else {

            foreach ($this->variants as $variant) {
                $total += $variant->quantity;
            }
        }
        return $total;
    }


    /**
     * Get product minimal price.
     *
     * @param  int  $qty
     * @return float
     */
    public function getBasePrice($qty = null)
    {
        if ($this->product_type == 'simple') {
            return $this->price;
        } else {
            return $this->getMaximumPrice();
        }
    }

    /**
     * Get product minimal price.
     *
     * @param  int  $qty
     * @return float
     */
    public function getMinimalPrice($qty = null)
    {
        $minPrice = null;
        if ($this->product_type == 'simple') {
            return $this->special_price && $this->special_price != 0 ? $this->special_price : $this->price;
        }
        if (!is_null($minPrice)) {
            return $minPrice;
        }

        /* method is calling many time so using variable */
        $tablePrefix = DB::getTablePrefix();

        $result = Product::distinct()
            ->where('products.parent_id', $this->id)
            ->selectRaw("IF( {$tablePrefix}products.special_price IS NULL
                            OR {$tablePrefix}products.special_price = 0 , {$tablePrefix}products.price,
                            LEAST( {$tablePrefix}products.special_price, {$tablePrefix}products.price )) AS min_price")
            ->get();

        $minPrices = [];

        foreach ($result as $price) {
            $minPrices[] = $price->min_price;
        }

        if (empty($minPrices)) {
            return 0;
        }

        return $minPrice = min($minPrices);
    }



    /**
     * Get product maximum price.
     *
     * @return float
     */
    public function getMaximumPrice()
    {
        $maxPrice = null;

        if (!is_null($maxPrice)) {
            return $maxPrice;
        }

        $product = Product::distinct()
            ->where('products.parent_id', $this->id)
            ->selectRaw('MAX(' . DB::getTablePrefix() . 'products.price) AS max_price')
            ->first();

        return $maxPrice = $product ? $product->max_price : 0;
    }

    /**
     * Get the product variants that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }



    /**
     * The super attributes that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function super_attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_super_attributes');
    }

    /**
     * The attributes that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributes(): BelongsToMany
    {
        return $this->parent ? $this->parent->belongsToMany(Attribute::class, 'product_attributes') : $this->belongsToMany(Attribute::class, 'product_attributes');
    }

    /**
     * Get the product attribute values that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attribute_values(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    /**
     * Retrieve product attributes.
     *
     * @param  bool  $skipSuperAttribute
     * @return \Illuminate\Support\Collection
     */
    public function getEditableAttributes()
    {
        $product = $this->parent ?: $this;
        if ($this->product_type == 'configurable') {
            $this->skipAttributes = array_merge(
                $product->super_attributes->pluck('code')->toArray(),
                $this->skipAttributes
            );
        }

        return $product->attributes()->whereNotIn(
            'attributes.code',
            $this->skipAttributes
        )->get();
    }

    /**
     * Get an product attribute value.
     *
     * @return mixed
     */
    public function getCustomAttributeValue($attribute)
    {

        if (!$attribute) {
            return;
        }

        $attributeValue = $this->attribute_values()
            ->where('attribute_id', $attribute->id)
            ->first();
        $attribute['value'] = $attributeValue[ProductAttributeValue::$attributeTypeFields[$attribute->type]] ?? null;
        if (in_array($attribute->type, ['select', 'multiselect', 'checkbox']) && $attribute['value']) {
            $attribute['value'] = new AttributeOptionResource(AttributeOption::find($attribute['value']));
        }
        return self::$loadedAttributeValues[$this->id][$attribute->id] = $attribute;
    }

    /**
     * Attributes to array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        if ($this->product_type == 'configurable') {
            $this->skipAttributes = array_merge(
                $this->super_attributes->pluck('code')->toArray(),
                $this->skipAttributes
            );
        } else if ($this->product_type == 'simple' && $this->parent) {
            $this->skipAttributes = getSingletonInstance(AttributeRepository::class)
                ->getProductAttributes($this, $this->parent->super_attributes->pluck('code')->toArray())->pluck('code')->toArray();
        }
        $attributes = getSingletonInstance(AttributeRepository::class)
            ->getProductAttributes($this, $this->skipAttributes);

        foreach ($attributes as $attribute) {

            $this->getCustomAttributeValue($attribute);
        }
        return ProductAttribute::collection($attributes);
    }

    public function permutation()
    {
        $attributes = parent::attributesToArray();

        if ($this->product_type == 'configurable') {
            $this->skipAttributes = array_merge(
                $this->super_attributes->pluck('code')->toArray(),
                $this->skipAttributes
            );
        } else if ($this->product_type == 'simple' && $this->parent) {
            $this->skipAttributes = getSingletonInstance(AttributeRepository::class)
                ->getProductAttributes($this, $this->parent->super_attributes->pluck('code')->toArray())->pluck('code')->toArray();
        }
        $attributes = getSingletonInstance(AttributeRepository::class)
            ->getProductAttributes($this, $this->skipAttributes);
        $permutation = [];
        foreach ($attributes as $attribute) {

            $this->getCustomAttributeValue($attribute);
            if (isset($attribute['value']) && $attribute['value'] && isset($attribute['value']['id']) && $attribute['value']['id'])
                $permutation[] = $attribute->id . ':' . $attribute['value']['id'];
        }
        return implode(",", $permutation);
    }


    /**
     * Check in loaded family attributes.
     *
     * @return object
     */
    public function checkInLoadedProductAttributes(): object
    {
        static $loadedProductAttributes = [];

        if (array_key_exists($this->id, $loadedProductAttributes)) {
            return $loadedProductAttributes[$this->id];
        }

        $this->skipAttributes = array_merge(
            $this->super_attributes->pluck('code')->toArray(),
            $this->skipAttributes
        );
        return $loadedProductAttributes[$this->id] = getSingletonInstance(AttributeRepository::class)
            ->getProductAttributes($this, $this->skipAttributes);
    }

    /**
     * Get product filter attributes.
     *
     * @return array
     */
    public function getProductUserFilterAttributes()
    {
        $user_id = auth()->user()->id;
        $attributes = Attribute::with(['options' => function ($query) use ($user_id) {
            return $query->where('user_id', $user_id);
        }])
            ->join('product_super_attributes', 'product_super_attributes.attribute_id', 'attributes.id')
            ->where('product_super_attributes.product_id', $this->id)
            ->get();
        foreach ($attributes as $attribute) {
            $db = DB::table('product_attribute_values')
                ->join('attribute_options', 'attribute_options.id', 'product_attribute_values.integer_value')
                ->selectRaw("GROUP_CONCAT(integer_value SEPARATOR ',') as ids, GROUP_CONCAT(name SEPARATOR ',') as names")
                ->where('product_attribute_values.attribute_id', $attribute->id)
                ->whereIn('product_id', $this->variants->pluck('id'))
                ->get()->toArray();
            $attribute['selected_options'] = $db;
        }
        return AttributeResource::collection($attributes);
    }

    /**
     * Handle quantity.
     *
     * @param  int  $quantity
     * @return int
     */
    public function handleQuantity(int $quantity): int
    {
        return !empty($quantity)
            ? $quantity
            : 1;
    }

    /**
     * Get request quantity.
     *
     * @param  array  $data
     * @return array
     */
    public function getQtyRequest($data)
    {
        if ($item = Cart::getItemByProduct(['additional' => $data])) {
            $data['quantity'] += $item->quantity;
        }

        return $data;
    }

    /**
     * Get product minimal price.
     *
     * @param  int  $qty
     * @return float
     */
    public function getFinalPrice($qty = null)
    {
        return round($this->getMinimalPrice($qty), 4);
    }

    /**
     * Have sufficient quantity.
     *
     * @param  int  $qty
     * @return bool
     */
    public function haveSufficientQuantity(int $qty): bool
    {
        if ($this->type == 'configurable') {
            foreach ($this->variants as $variant) {
                if ($variant->haveSufficientQuantity($qty)) {
                    return true;
                }
            }
        }
        return $qty <= $this->totalQuantity();
    }

    /**
     * Is item have quantity.
     *
     * @param \App\Models\CartItem  $cartItem
     * @return bool
     */
    public function isItemHaveQuantity($cartItem)
    {
        return $cartItem->product->haveSufficientQuantity($cartItem->quantity);
    }

    /**
     * Returns true, if cart item is inactive.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem  $item
     * @return bool
     */
    public function isCartItemInactive(\App\Models\CartItem $item): bool
    {
        if (!$item->product->status) {
            return true;
        }

        switch ($item->product->type) {
            case 'configurable':
                if (
                    $item->child
                    && !$item->child->product->status
                ) {
                    return true;
                }
                break;
        }

        return false;
    }

    /**
     * Validate cart item product price and other things.
     *
     * @param  \App\Models\CartItem  $item
     */
    public function validateCartItem(CartItem $item)
    {
        if ($this->isCartItemInactive($item)) {
            return false;
        }

        $price = round($item->product->getFinalPrice($item->quantity), 4);

        if ($price == $item->base_price) {
            return true;
        }

        $item->base_price = $price;
        $item->price = convertPrice($price);

        $item->base_total = $price * $item->quantity;
        $item->total = convertPrice($price * $item->quantity);

        $item->save();

        return true;
    }

    /**
     * Compare options.
     *
     * @param  array  $options1
     * @param  array  $options2
     * @return bool
     */
    public function compareOptions($options1, $options2)
    {
        if ($this->type == 'configurable') {
            if ($this->product->id != $options2['product_id']) {
                return false;
            }

            if (
                isset($options1['selected_configurable_option'])
                && isset($options2['selected_configurable_option'])
            ) {
                return $options1['selected_configurable_option'] === $options2['selected_configurable_option'];
            }

            if (!isset($options1['selected_configurable_option'])) {
                return false;
            }

            if (!isset($options2['selected_configurable_option'])) {
                return false;
            }
        } else {
            if ($this->id != $options2['product_id']) {
                return false;
            } else {
                if (
                    isset($options1['parent_id'])
                    && isset($options2['parent_id'])
                ) {
                    if ($options1['parent_id'] == $options2['parent_id']) {
                        return true;
                    } else {
                        return false;
                    }
                } elseif (
                    isset($options1['parent_id'])
                    && !isset($options2['parent_id'])
                ) {
                    return false;
                } elseif (
                    isset($options2['parent_id'])
                    && !isset($options1['parent_id'])
                ) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Add product. Returns error message if can't prepare product.
     *
     * @param  array  $data
     * @return array
     */
    public function prepareForCart($data)
    {
        $data['quantity'] = $this->handleQuantity((int) $data['quantity']);

        $data = $this->getQtyRequest($data);

        if (!$this->haveSufficientQuantity($data['quantity'])) {
            return trans('The requested quantity is not available.');
        }

        $price = $this->getFinalPrice();

        $products = [
            [
                'product_id'        => $this->id,
                'quantity'          => $data['quantity'],
                'name'              => $this->name,
                'price'             => $convertedPrice = convertPrice($price),
                'base_price'        => $price,
                'total'             => $convertedPrice * $data['quantity'],
                'base_total'        => $price * $data['quantity'],
                'type'              => $this->type,
            ],
        ];

        return $products;
    }
}
