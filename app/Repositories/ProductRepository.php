<?php

namespace App\Repositories;

use Illuminate\Container\Container;
use App\Repositories\AttributeRepository;
use App\Eloquent\Repository;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductAttributeValue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class ProductRepository extends Repository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name' => 'like',
        'type' => 'like',
        'created_at' => 'like',
        'variants.name' => 'like'
    ];

    /**
     * Create a new repository instance.
     *
     * @param  \App\Repositories\AttributeRepository  $attributeRepository
     * @param  \App\Repositories\AttributeOptionRepository  $attributeOptionRepository
     * @param  \App\Repositories\ProductAttributeValueRepository  $attributeValueRepository
     * @param  \App\Repositories\ProductImageRepository  $attributeImageRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected AttributeOptionRepository $attributeOptionRepository,
        protected ProductAttributeValueRepository $attributeValueRepository,
        protected ProductImageRepository $attributeImageRepository,
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
        return 'App\Models\Product';
    }

    /**
     * Get all products.
     *
     * @param  string  $categoryId
     * @return \Illuminate\Support\Collection
     */
    public function getAll($type = false)
    {
        $params = request()->input();
        $perPage = isset($params['limit']) && !empty($params['limit']) ? $params['limit'] : 9;

        $page = Paginator::resolveCurrentPage('page');

        $repository = $this->with([
            'images',
        ])
            ->scopeQuery(function ($query) use ($params, $type) {

                $qb = $query->distinct()
                    ->whereNull('parent_id')
                    ->orderBy('products.created_at',  'desc')
                    ->where(function ($query) {
                        if (auth()->user())
                            return $query->where('user_id', auth()->user()->id);
                    })
                    ->select('products.*');


                if (!$type)
                    $qb->where('products.status', 'active');
                else if (request()->input('status')) {
                    $qb->where('products.status', request()->input('status'));
                }
                if (isset($params['search'])) {
                    $qb->where('products.name', 'like', '%' . urldecode($params['search']) . '%');
                }

                if (isset($params['type'])) {
                    $qb->where('products.type',  urldecode($params['type']));
                }

                if (isset($params['name'])) {
                    $qb->where('products.name', 'like', '%' . urldecode($params['name']) . '%');
                }

                # sort direction
                $orderDirection = 'asc';

                if (
                    isset($params['order'])
                    && in_array($params['order'], ['desc', 'asc'])
                ) {
                    $orderDirection = $params['order'];
                } else {
                    $sortOptions = $this->getDefaultSortByOption();

                    $orderDirection = !empty($sortOptions) ? $sortOptions[1] : 'asc';
                }

                if ($priceFilter = request('price')) {
                    $priceRange = explode(',', $priceFilter);

                    if (count($priceRange) > 0) {

                        $this->variantJoin($qb);

                        $qb
                            ->leftJoin('catalog_rule_product_prices', 'catalog_rule_product_prices.product_id', '=', 'variants.product_id')
                            ->leftJoin('product_customer_group_prices', 'product_customer_group_prices.product_id', '=', 'variants.product_id')
                            ->where(function ($qb) use ($priceRange) {
                                $qb->where(function ($qb) use ($priceRange) {
                                    $qb
                                        ->where('variants.min_price', '>=', $priceRange[0])
                                        ->where('variants.min_price', '<=', end($priceRange));
                                });
                            });
                    }
                }

                return $qb->groupBy('products.id');
            });

        # apply scope query so we can fetch the raw sql and perform a count
        $repository->applyScope();
        $countQuery = "select count(*) as aggregate from ({$repository->model->toSql()}) c";
        $count = collect(DB::select($countQuery, $repository->model->getBindings()))->pluck('aggregate')->first();

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $repository->scopeQuery(function ($query) use ($page, $perPage) {
                return $query->forPage($page, $perPage);
            });

            # manually build the paginator
            $items = $repository->get();
        } else {
            $items = [];
        }

        // $results = new LengthAwarePaginator($items, $count, $perPage, $page, [
        //     'path'  => request()->url(),
        //     'query' => request()->query(),
        // ]);

        return $items;
    }

    /**
     * Get all products.
     *
     * @param  string  $categoryId
     * @return \Illuminate\Support\Collection
     */
    public function getProducts($type = null)
    {
        $params = request()->input();
        $perPage = isset($params['limit']) && !empty($params['limit']) ? $params['limit'] : 10;

        $page = Paginator::resolveCurrentPage('page');

        $repository = $this->with([
            'images',
        ])->scopeQuery(function ($query) use ($params, $type) {

            $qb = $query->distinct()
                ->whereNull('parent_id')
                ->orderBy('products.created_at',  'desc')
                ->select('products.*');

            if ($type) {
                $qb->where('products.type', $type);
            }

            # sort direction
            $orderDirection = 'desc';

            if (
                isset($params['order'])
                && in_array($params['order'], ['desc', 'asc'])
            ) {
                $orderDirection = $params['order'];
            } else {
                $sortOptions = $this->getDefaultSortByOption();

                $orderDirection = !empty($sortOptions) ? $sortOptions[1] : 'desc';
            }

            $qb->orderBy('products.id', $orderDirection);

            return $qb->groupBy('products.id');
        });

        # apply scope query so we can fetch the raw sql and perform a count
        $repository->applyScope();
        $countQuery = "select count(*) as aggregate from ({$repository->model->toSql()}) c";
        $count = collect(DB::select($countQuery, $repository->model->getBindings()))->pluck('aggregate')->first();

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $repository->scopeQuery(function ($query) use ($page, $perPage) {
                return $query->forPage($page, $perPage);
            });

            # manually build the paginator
            $items = $repository->get();
        } else {
            $items = [];
        }

        return $items;
    }

    /**
     * Get default sort by option.
     *
     * @return array
     */
    private function getDefaultSortByOption()
    {
        $config = 'name-desc';

        return explode('-', $config);
    }

    /**
     * Create product.
     *
     * @param  array  $data
     * @return \App\Models\Product
     */
    public function create(array $data)
    {

        $data['user_id'] = Auth::user()->id;
        if (Auth::user()->type == 2) {
            $data['status'] = 'active';
        } else {
            $data['status'] = 'pending';
        }
        if (!isset($data['brand_id']) && isset($data['brand_name'])) {
            $category = Category::findOrFail($data['category_id']);
            $brand = Brand::create(['name' => $data['brand_name']]);
            $category->brands()->attach($brand->id);
            $data['brand_id'] = $brand->id;
        }
        $product = $this->model->create($data);
        $attributes = isset($data['attributes']) ? $data['attributes'] : [];
        foreach ($attributes as $attributeData) {

            $attribute = $this->attributeRepository->findOrFail($attributeData['attribute_id']);
            if (isset($attributeData['is_new']) && $attributeData['is_new'] && $attribute->type === 'select') {
                $attributeOption = $this->attributeOptionRepository
                    ->where('name', $attributeData['value'])
                    ->where('user_id', $data['user_id'])
                    ->where('attribute_id', $attribute->id)
                    ->first();
                if (!$attributeOption) {
                    $attributeOption = $this->attributeOptionRepository->create([
                        'name' => $attributeData['value'],
                        'attribute_id' => $attribute->id,
                        'user_id' => $data['user_id'],
                    ]);
                }

                $data[$attribute->code] = $attributeOption->id;
            } else {
                $data[$attribute->code] = $attributeData['value'];
            }

            if (
                $attribute->type === 'boolean'
            ) {
                $data[$attribute->code] = isset($data[$attribute->code]) && $data[$attribute->code] ? 1 : 0;
            }

            if (
                $attribute->type == 'multiselect'
                || $attribute->type == 'checkbox'
            ) {
                $data[$attribute->code] = isset($data[$attribute->code]) ? implode(',', $data[$attribute->code]) : null;
            }

            if (!isset($data[$attribute->code])) {
                continue;
            }

            if (
                $attribute->type === 'price'
                && isset($data[$attribute->code])
                && $data[$attribute->code] === ''
            ) {
                $data[$attribute->code] = null;
            }

            if (
                $attribute->type === 'date'
                && $data[$attribute->code] === ''
            ) {
                $data[$attribute->code] = null;
            }

            if (
                $attribute->type === 'image'
                || $attribute->type === 'file'
            ) {
                $data[$attribute->code] = gettype($data[$attribute->code]) === 'object'
                    ? request()->file($attribute->code)->store('product/' . $product->id)
                    : null;
            }

            $productAttributeValue = $product->attribute_values
                ->where('attribute_id', $attribute->id)
                ->first();

            $columnName = ProductAttributeValue::$attributeTypeFields[$attribute->type];

            if (!$productAttributeValue) {
                $this->attributeValueRepository->create([
                    'product_id'   => $product->id,
                    'attribute_id' => $attribute->id,
                    'user_id'      => Auth::user()->id,
                    $columnName    => $data[$attribute->code],
                ]);
            } else {
                $productAttributeValue->update([$columnName => $data[$attribute->code]]);
            }
        }

        if (isset($data['variants'])) {
            foreach ($data['variants'] as $variantData) {
                $variantId = isset($variantData['id']) ? $variantData['id'] : 'variant_';
                if (isset($variantData['super_attributes'])) {

                    foreach ($variantData['super_attributes'] as $super_attribute) {

                        $attribute = $this->attributeRepository->findOrFail($super_attribute['attribute_id']);

                        $product->super_attributes()->syncWithoutDetaching([$attribute->id]);

                        $product->attributes()->syncWithoutDetaching([$attribute->id]);
                    }
                }

                // if (isset($variantData['super_attributes'])) {
                //     $variantData['attributes'] = $data['attributes'];
                // }

                $this->createVariant($product, $variantData);
            }
        }

        if (isset($data['attributes'])) {

            foreach ($data['attributes'] as $position => $attribute) {

                $attribute = $this->attributeRepository->findOrFail($attribute['attribute_id']);

                $product->attributes()->syncWithoutDetaching($attribute->id);
            }
        }

        $this->attributeImageRepository->uploadImages($data, $product);

        return $product;
    }

    /**
     * Create variant.
     *
     * @param  \App\Models\Product   $product
     * @param  array                    $data
     * @return \App\Models\Product
     */
    public function createVariant($product, $data = [])
    {

        $data['user_id'] = Auth::user()->id;
        $variant = $this->model->create(
            array_merge([
                'parent_id'             => $product->id,
                'user_id'               => $data['user_id'],
                'type'                  => $product->type,
                'category_id'           => $product->category_id,
                'brand_id'              => $product->brand_id,
                'product_type'          => 'simple',
            ], $data)
        );

        $attributes = array_merge($data['super_attributes'], []);
        foreach ($attributes as $attributeData) {

            $attribute = $this->attributeRepository->findOrFail($attributeData['attribute_id']);
            if (isset($attributeData['is_new']) && $attributeData['is_new'] && $attribute->type === 'select') {
                $attributeOption = $this->attributeOptionRepository
                    ->where('name', $attributeData['value'])
                    ->where('user_id', $data['user_id'])
                    ->where('attribute_id', $attribute->id)
                    ->first();
                if (!$attributeOption) {
                    $attributeOption = $this->attributeOptionRepository->create([
                        'name' => $attributeData['value'],
                        'attribute_id' => $attribute->id,
                        'user_id' => $data['user_id'],
                    ]);
                }
                $data[$attribute->code] = $attributeOption->id;
            } else {
                $data[$attribute->code] = $attributeData['value'];
            }

            if (
                $attribute->type === 'boolean'
            ) {
                $data[$attribute->code] = isset($data[$attribute->code]) && $data[$attribute->code] ? 1 : 0;
            }

            if (
                $attribute->type == 'multiselect'
                || $attribute->type == 'checkbox'
            ) {
                $data[$attribute->code] = isset($data[$attribute->code]) ? implode(',', $data[$attribute->code]) : null;
            }

            if (!isset($data[$attribute->code])) {
                continue;
            }

            if (
                $attribute->type === 'price'
                && isset($data[$attribute->code])
                && $data[$attribute->code] === ''
            ) {
                $data[$attribute->code] = null;
            }

            if (
                $attribute->type === 'date'
                && $data[$attribute->code] === ''
            ) {
                $data[$attribute->code] = null;
            }

            if (
                $attribute->type === 'image'
                || $attribute->type === 'file'
            ) {
                $data[$attribute->code] = gettype($data[$attribute->code]) === 'object'
                    ? request()->file($attribute->code)->store('product/' . $variant->id)
                    : null;
            }

            $productAttributeValue = $variant->attribute_values
                ->where('attribute_id', $attribute->id)
                ->first();

            $columnName = ProductAttributeValue::$attributeTypeFields[$attribute->type];

            if (!$productAttributeValue) {
                $this->attributeValueRepository->create([
                    'product_id'   => $variant->id,
                    'attribute_id' => $attribute->id,
                    'user_id'      => Auth::user()->id,
                    $columnName    => $data[$attribute->code],
                ]);
            } else {
                $productAttributeValue->update([$columnName => $data[$attribute->code]]);
            }
        }

        return $variant;
    }

    /**
     * Update product.
     *
     * @param  array  $data
     * @param  int  $id
     * @return \App\Models\Product
     */
    public function update(array $data, $id)
    {
        $product = $this->findOrFail($id);
        $data['user_id'] = Auth::user()->id;
        if (!isset($data['brand_id']) && isset($data['brand_name'])) {
            $category = Category::findOrFail($data['category_id']);
            $brand = Brand::create(['name' => $data['brand_name']]);
            $category->brands()->attach($brand->id);
            $data['brand_id'] = $brand->id;
        }
        $product->update($data);

        $attributes = isset($data['attributes']) ? $data['attributes'] : [];
        foreach ($attributes as $attributeData) {

            $attribute = $this->attributeRepository->findOrFail($attributeData['attribute_id']);
            if (isset($attributeData['is_new']) && $attributeData['is_new'] && $attribute->type === 'select') {
                $attributeOption = $this->attributeOptionRepository
                    ->where('name', $attributeData['value'])
                    ->where('user_id', $data['user_id'])
                    ->where('attribute_id', $attribute->id)
                    ->first();
                if (!$attributeOption) {
                    $attributeOption = $this->attributeOptionRepository->create([
                        'name' => $attributeData['value'],
                        'attribute_id' => $attribute->id,
                        'user_id' => $data['user_id'],
                    ]);
                }
                $data[$attribute->code] = $attributeOption->id;
            } else {
                $data[$attribute->code] = $attributeData['value'];
            }

            if (
                $attribute->type === 'boolean'
            ) {
                $data[$attribute->code] = isset($data[$attribute->code]) && $data[$attribute->code] ? 1 : 0;
            }

            if (
                $attribute->type == 'multiselect'
                || $attribute->type == 'checkbox'
            ) {
                $data[$attribute->code] = isset($data[$attribute->code]) ? implode(',', $data[$attribute->code]) : null;
            }

            if (!isset($data[$attribute->code])) {
                continue;
            }

            if (
                $attribute->type === 'price'
                && isset($data[$attribute->code])
                && $data[$attribute->code] === ''
            ) {
                $data[$attribute->code] = null;
            }

            if (
                $attribute->type === 'date'
                && $data[$attribute->code] === ''
            ) {
                $data[$attribute->code] = null;
            }

            if (
                $attribute->type === 'image'
                || $attribute->type === 'file'
            ) {
                $data[$attribute->code] = gettype($data[$attribute->code]) === 'object'
                    ? request()->file($attribute->code)->store('product/' . $product->id)
                    : null;
            }

            $productAttributeValue = $product->attribute_values
                ->where('attribute_id', $attribute->id)
                ->first();

            $columnName = ProductAttributeValue::$attributeTypeFields[$attribute->type];

            if (!$productAttributeValue) {
                $this->attributeValueRepository->create([
                    'product_id'   => $product->id,
                    'attribute_id' => $attribute->id,
                    'user_id'      => Auth::user()->id,
                    $columnName    => $data[$attribute->code],
                ]);
            } else {
                $productAttributeValue->update([$columnName => $data[$attribute->code]]);
            }
        }

        $old_variants = $product->variants()->pluck('id');

        if (isset($data['variants'])) {
            foreach ($data['variants'] as $variantData) {
                if (isset($variantData['super_attributes'])) {

                    foreach ($variantData['super_attributes'] as $super_attribute) {

                        $attribute = $this->attributeRepository->findOrFail($super_attribute['attribute_id']);

                        $product->super_attributes()->syncWithoutDetaching([$attribute->id]);

                        $product->attributes()->syncWithoutDetaching([$attribute->id]);
                    }
                }

                if (isset($variantData['id'])) {
                    $variantId = $variantData['id'];
                    if (is_numeric($index = $old_variants->search($variantId))) {
                        $old_variants->forget($index);
                    }
                    $this->updateVariant($product, $variantData);
                } else {
                    $this->createVariant($product, $variantData);
                }
            }
            foreach ($old_variants as $variantId) {
                $this->delete($variantId);
            }
        }

        if (isset($data['attributes'])) {

            foreach ($data['attributes'] as $position => $attribute) {

                $attribute = $this->attributeRepository->findOrFail($attribute['attribute_id']);

                $product->attributes()->syncWithoutDetaching($attribute->id);
            }
        }

        $this->attributeImageRepository->uploadImages($data, $product);

        return $product;
    }

    /**
     * Create variant.
     *
     * @param  \Webkul\Models\Product   $product
     * @param  array                    $data
     * @return \Webkul\Models\Product
     */
    public function updateVariant($product, $data = [])
    {
        $data['user_id'] = Auth::user()->id;
        $variant = $this->findOrFail($data['id']);
        $variant->update(
            array_merge([
                'parent_id'             => $product->id,
                'user_id'               => $data['user_id'],
                'type'                  => $product->type,
                'category_id'           => $product->category_id,
                'brand_id'              => $product->brand_id,
                'product_type'          => 'simple',
            ], $data)
        );

        $attributes = array_merge($data['super_attributes'], []);
        foreach ($attributes as $attributeData) {

            $attribute = $this->attributeRepository->findOrFail($attributeData['attribute_id']);
            if (isset($attributeData['is_new']) && $attributeData['is_new'] && $attribute->type === 'select') {
                $attributeOption = $this->attributeOptionRepository
                    ->where('name', $attributeData['value'])
                    ->where('user_id', $data['user_id'])
                    ->where('attribute_id', $attribute->id)
                    ->first();
                if (!$attributeOption) {
                    $attributeOption = $this->attributeOptionRepository->create([
                        'name' => $attributeData['value'],
                        'attribute_id' => $attribute->id,
                        'user_id' => $data['user_id'],
                    ]);
                }
                $data[$attribute->code] = $attributeOption->id;
            } else {
                $data[$attribute->code] = $attributeData['value'];
            }

            if (
                $attribute->type === 'boolean'
            ) {
                $data[$attribute->code] = isset($data[$attribute->code]) && $data[$attribute->code] ? 1 : 0;
            }

            if (
                $attribute->type == 'multiselect'
                || $attribute->type == 'checkbox'
            ) {
                $data[$attribute->code] = isset($data[$attribute->code]) ? implode(',', $data[$attribute->code]) : null;
            }

            if (!isset($data[$attribute->code])) {
                continue;
            }

            if (
                $attribute->type === 'price'
                && isset($data[$attribute->code])
                && $data[$attribute->code] === ''
            ) {
                $data[$attribute->code] = null;
            }

            if (
                $attribute->type === 'date'
                && $data[$attribute->code] === ''
            ) {
                $data[$attribute->code] = null;
            }

            if (
                $attribute->type === 'image'
                || $attribute->type === 'file'
            ) {
                $data[$attribute->code] = gettype($data[$attribute->code]) === 'object'
                    ? request()->file($attribute->code)->store('product/' . $variant->id)
                    : null;
            }

            $productAttributeValue = $variant->attribute_values
                ->where('attribute_id', $attribute->id)
                ->first();

            $columnName = ProductAttributeValue::$attributeTypeFields[$attribute->type];

            if (!$productAttributeValue) {
                $this->attributeValueRepository->create([
                    'product_id'   => $variant->id,
                    'attribute_id' => $attribute->id,
                    'user_id'      => Auth::user()->id,
                    $columnName    => $data[$attribute->code],
                ]);
            } else {
                $productAttributeValue->update([$columnName => $data[$attribute->code]]);
            }
        }

        return $variant;
    }

    public function getNewArrivals($type = null)
    {
        $params = request()->input();
        $perPage = isset($params['limit']) && !empty($params['limit']) ? $params['limit'] : 20;
        $page = Paginator::resolveCurrentPage('page');

        $repository = $this->with([
            'images',
        ])->scopeQuery(function ($query) use ($params, $type) {
            $priceFilter =  $params['price'] ?? null;
            $order = $params['order'] ?? 'newest_first';
            $qb = $query
                ->distinct()
                ->select('products.*', DB::raw('IF(products.product_type = "simple" AND products.parent_id IS NULL, IF( products.special_price < products.price AND products.special_price > 0,
                products.special_price,
                products.price), 
                (SELECT MIN(
                    IF(
                        p.special_price < p.price AND p.special_price > 0,
                        p.special_price,
                        p.price
                    )
                    ) FROM products p WHERE p.parent_id = products.id)) AS min_price
                
                '))
                ->whereNull('products.parent_id');
            $qb
                ->when($priceFilter, function ($query) use ($priceFilter) {
                    $priceRange = explode(',', $priceFilter);
                    if (count($priceRange) > 1) {
                        $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                            ->where('variants.product_type', '=', 'simple')
                            ->where(function ($query) use ($priceRange) {
                                $query->whereBetween('variants.price', $priceRange)
                                    ->orWhere(function ($query2) use ($priceRange) {
                                        $query2->where('variants.special_price', '>', '0');
                                        $query2->whereBetween('variants.special_price', $priceRange);
                                    });
                            });
                        // dd(count($query->get()));
                    } else if (count($priceRange) == 1) {
                        $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                            ->where('variants.product_type', '=', 'simple')
                            ->where(function ($query) use ($priceRange) {
                                $query->where('variants.price', '>=', $priceRange[0])
                                    ->orWhere(function ($query2) use ($priceRange) {
                                        $query2->where('variants.special_price', '>', '0');
                                        $query2->where('variants.special_price', '>=', $priceRange[0]);
                                    });
                            });
                    }
                })
                ->when($order, function ($query) use ($order) {
                    if ($order == 'newest_first') {
                        $query = $query->orderBy('products.created_at',  'desc');
                    } else if ($order == 'low_to_high') {
                        $query->orderBy('min_price');
                    } else if ($order == 'high_to_low') {
                        $query->orderByDesc('min_price');
                    } else {
                        $query->orderBy('products.created_at',  'desc');
                    }
                });
            // dd($qb->toSql());
            if (is_null(request()->input('status'))) {
                $qb->where('products.status', 'active');
            }

            if (isset($params['search'])) {
                $qb->where('products.name', 'like', '%' . urldecode($params['search']) . '%');
            }


            return $qb->groupBy('products.id');
        });

        # apply scope query so we can fetch the raw sql and perform a count
        $repository->applyScope();
        $countQuery = "select count(*) as aggregate from ({$repository->model->toSql()}) c";
        $count = collect(DB::select($countQuery, $repository->model->getBindings()))->pluck('aggregate')->first();

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $repository->scopeQuery(function ($query) use ($page, $perPage) {
                return $query->forPage($page, $perPage);
            });

            # manually build the paginator
            $items = $repository->get();
        } else {
            $items = [];
        }

        return $items;
    }


    //TODO get product having most sold quantities
    public function getBestSellers($type = null)
    {
        $params = request()->input();
        $perPage = isset($params['limit']) && !empty($params['limit']) ? $params['limit'] : 20;


        $page = Paginator::resolveCurrentPage('page');

        $repository = $this->with([
            'images',
        ])->scopeQuery(function ($query) use ($params, $type) {
            $priceFilter =  $params['price'] ?? null;
            $order = $params['order'] ?? 'newest_first';

            $qb = $query
                ->distinct()
                ->select('products.*', DB::raw('IF(products.product_type = "simple" AND products.parent_id IS NULL, IF( products.special_price < products.price AND products.special_price > 0,
                products.special_price,
                products.price), 
                (SELECT MIN(
                    IF(
                        p.special_price < p.price AND p.special_price > 0,
                        p.special_price,
                        p.price
                    )
                    ) FROM products p WHERE p.parent_id = products.id)) AS min_price
                '))
                // ->orderBy('products.created_at',  'desc')
                ->whereNull('products.parent_id');
            $qb
                ->when($priceFilter, function ($query) use ($priceFilter) {
                    $priceRange = explode(',', $priceFilter);
                    if (count($priceRange) > 1) {
                        $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                            ->where('variants.product_type', '=', 'simple')
                            ->where(function ($query) use ($priceRange) {
                                $query->whereBetween('variants.price', $priceRange)
                                    ->orWhere(function ($query2) use ($priceRange) {
                                        $query2->where('variants.special_price', '>', '0');
                                        $query2->whereBetween('variants.special_price', $priceRange);
                                    });
                            });
                        // dd(count($query->get()));
                    } else if (count($priceRange) == 1) {
                        $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                            ->where('variants.product_type', '=', 'simple')
                            ->where(function ($query) use ($priceRange) {
                                $query->where('variants.price', '>=', $priceRange[0])
                                    ->orWhere(function ($query2) use ($priceRange) {
                                        $query2->where('variants.special_price', '>', '0');
                                        $query2->where('variants.special_price', '>=', $priceRange[0]);
                                    });
                            });
                    }
                })
                ->when($order, function ($query) use ($order) {
                    if ($order == 'newest_first') {
                        $query = $query->orderBy('products.created_at',  'desc');
                    } else if ($order == 'low_to_high') {
                        $query->orderBy('min_price');
                    } else if ($order == 'high_to_low') {
                        $query->orderByDesc('min_price');
                    } else {
                        $query->orderBy('products.created_at',  'desc');
                    }
                });
            if (is_null(request()->input('status'))) {
                $qb->where('products.status', 'active');
            }

            if (isset($params['search'])) {
                $qb->where('products.name', 'like', '%' . urldecode($params['search']) . '%');
            }



            // if (isset($params['category'])) {
            //     if ($categoryFilter = request('category')) {
            //         $category = explode(',', $categoryFilter);
            //         $qb->join('categories', 'categories.id', 'products.category_id')
            //             ->join('categories', 'categories.id', 'categories.category_id')
            //             ->whereIn('categories.id', $category);
            //     }
            // }
            return $qb->groupBy('products.id');
        });

        # apply scope query so we can fetch the raw sql and perform a count
        $repository->applyScope();
        $countQuery = "select count(*) as aggregate from ({$repository->model->toSql()}) c";
        $count = collect(DB::select($countQuery, $repository->model->getBindings()))->pluck('aggregate')->first();

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $repository->scopeQuery(function ($query) use ($page, $perPage) {
                return $query->forPage($page, $perPage);
            });

            # manually build the paginator
            $items = $repository->get();
        } else {
            $items = [];
        }

        return $items;
    }

    public function getProductsByIds()
    {
        $ids = request()->get('ids');
        $products = Product::select('products.*')
            ->whereIn('products.id', explode(",", $ids))
            ->get();
        return $products;
    }


    public function getSpecialOffers($type = null)
    {
        $params = request()->input();
        $perPage = isset($params['limit']) && !empty($params['limit']) ? $params['limit'] : 20;

        // $parents_of_variants_having_special_price =
        //     DB::table('products')->select('parent_id')
        //     ->where('products.special_price', '!=', '0')
        //     ->whereColumn('products.special_price', '<', 'products.price')
        //     ->distinct();

        $page = Paginator::resolveCurrentPage('page');

        $repository = $this->with([
            'images',
        ])->scopeQuery(function ($query) use ($params, $type) {
            $priceFilter =  $params['price'] ?? null;
            $order = $params['order'] ?? 'newest_first';
            $qb = $query
                ->distinct()
                ->select('products.*', DB::raw('IF(products.product_type = "simple" AND products.parent_id IS NULL, IF( products.special_price < products.price AND products.special_price > 0,
                products.special_price,
                products.price), 
                (SELECT MIN(
                    IF(
                        p.special_price < p.price AND p.special_price > 0,
                        p.special_price,
                        p.price
                    )
                    ) FROM products p WHERE p.parent_id = products.id)) AS min_price
                '))
                ->where(function ($query) {
                    $query->where(function ($query2) {
                        $query2->where('products.product_type', '=', 'simple');
                        $query2->where('products.special_price', '>', '0');
                    })
                        ->orWhere(function ($query2) {
                            $query2->where('products.product_type', '=', 'configurable');
                            $query2->whereRaw(
                                'products.id IN (select parent_id from products where product_type=\'simple\' and special_price>0)'
                            );
                        });
                })
                ->whereNull('products.parent_id')
                ->when($priceFilter, function ($query) use ($priceFilter) {
                    $priceRange = explode(',', $priceFilter);
                    if (count($priceRange) > 1) {
                        $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                            ->where('variants.product_type', '=', 'simple')
                            ->where(function ($query) use ($priceRange) {
                                $query->whereBetween('variants.price', $priceRange)
                                    ->orWhere(function ($query2) use ($priceRange) {
                                        $query2->where('variants.special_price', '>', '0');
                                        $query2->whereBetween('variants.special_price', $priceRange);
                                    });
                            });
                        // dd(count($query->get()));
                    } else if (count($priceRange) == 1) {
                        $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                            ->where('variants.product_type', '=', 'simple')
                            ->where(function ($query) use ($priceRange) {
                                $query->where('variants.price', '>=', $priceRange[0])
                                    ->orWhere(function ($query2) use ($priceRange) {
                                        $query2->where('variants.special_price', '>', '0');
                                        $query2->where('variants.special_price', '>=', $priceRange[0]);
                                    });
                            });
                    }
                })
                ->when($order, function ($query) use ($order) {
                    if ($order == 'newest_first') {
                        $query = $query->orderBy('products.created_at',  'desc');
                    } else if ($order == 'low_to_high') {
                        $query->orderBy('min_price');
                    } else if ($order == 'high_to_low') {
                        $query->orderByDesc('min_price');
                    } else {
                        $query->orderBy('products.created_at',  'desc');
                    }
                });

            if (is_null(request()->input('status'))) {
                $qb->where('products.status', 'active');
            }

            if (isset($params['search'])) {
                $qb->where('products.name', 'like', '%' . urldecode($params['search']) . '%');
            }

            // if (isset($params['price'])) {
            //     if ($priceFilter = request('price')) {
            //         $priceRange = explode(',', $priceFilter);

            //         if (count($priceRange) > 1) {
            //             $qb
            //                 ->where('products.price', '>=', $priceRange[0])
            //                 ->where('products.price', '<=', end($priceRange));
            //         } else {
            //             $qb
            //                 ->where('products.price', '>=', $priceRange);
            //         }
            //     }
            // }

            // if (isset($params['order'])) {
            //     if (urldecode($params['order']) == 'newest_first') {
            //         $qb->orderBy('products.created_at',  'desc');
            //     } else if (urldecode($params['order']) == 'low_to_high') {
            //         $qb->orderBy('products.price', 'asc');
            //     } else if (urldecode($params['order']) == 'high_to_low') {
            //         $qb->orderBy('products.price',  'desc');
            //     }
            // } else {
            //     $qb->orderBy('products.created_at',  'desc');
            // }

            // if (isset($params['category'])) {
            //     if ($categoryFilter = request('category')) {
            //         $category = explode(',', $categoryFilter);
            //         $qb->join('categories', 'categories.id', 'products.category_id')
            //             ->join('categories', 'categories.id', 'categories.category_id')
            //             ->whereIn('categories.id', $category);
            //     }
            // }
            return $qb->groupBy('products.id');
        });

        # apply scope query so we can fetch the raw sql and perform a count
        $repository->applyScope();
        $countQuery = "select count(*) as aggregate from ({$repository->model->toSql()}) c";
        $count = collect(DB::select($countQuery, $repository->model->getBindings()))->pluck('aggregate')->first();

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $repository->scopeQuery(function ($query) use ($page, $perPage) {
                return $query->forPage($page, $perPage);
            });

            # manually build the paginator
            $items = $repository->get();
        } else {
            $items = [];
        }

        return $items;
    }

    public function getFeaturedItems($type = null)
    {
        $params = request()->input();
        $perPage = isset($params['limit']) && !empty($params['limit']) ? $params['limit'] : 20;

        $page = Paginator::resolveCurrentPage('page');

        $repository = $this->with([
            'images',
        ])->scopeQuery(function ($query) use ($params, $type) {
            $priceFilter =  $params['price'] ?? null;
            $order = $params['order'] ?? 'newest_first';
            $qb = $query
                ->distinct()
                ->select('products.*', DB::raw('IF(products.product_type = "simple" AND products.parent_id IS NULL, IF( products.special_price < products.price AND products.special_price > 0,
                products.special_price,
                products.price), 
                (SELECT MIN(
                    IF(
                        p.special_price < p.price AND p.special_price > 0,
                        p.special_price,
                        p.price
                    )
                    ) FROM products p WHERE p.parent_id = products.id)) AS min_price
                '))
                ->whereRaw(
                    'products.id IN (select product_id from featured_products)'
                )->whereNull('products.parent_id');
            // dd(count($qb->get()));
            $qb->when($priceFilter, function ($query) use ($priceFilter) {
                $priceRange = explode(',', $priceFilter);
                if (count($priceRange) > 1) {
                    $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                        ->where('variants.product_type', '=', 'simple')
                        ->where(function ($query) use ($priceRange) {
                            $query->whereBetween('variants.price', $priceRange)
                                ->orWhere(function ($query2) use ($priceRange) {
                                    $query2->where('variants.special_price', '>', '0');
                                    $query2->whereBetween('variants.special_price', $priceRange);
                                });
                        });
                    // dd(count($query->get()));
                } else if (count($priceRange) == 1) {
                    $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                        ->where('variants.product_type', '=', 'simple')
                        ->where(function ($query) use ($priceRange) {
                            $query->where('variants.price', '>=', $priceRange[0])
                                ->orWhere(function ($query2) use ($priceRange) {
                                    $query2->where('variants.special_price', '>', '0');
                                    $query2->where('variants.special_price', '>=', $priceRange[0]);
                                });
                        });
                }
            })
                ->when($order, function ($query) use ($order) {
                    if ($order == 'newest_first') {
                        $query = $query->orderBy('products.created_at',  'desc');
                    } else if ($order == 'low_to_high') {
                        $query->orderBy('min_price');
                    } else if ($order == 'high_to_low') {
                        $query->orderByDesc('min_price');
                    } else {
                        $query->orderBy('products.created_at',  'desc');
                    }
                });
            if (is_null(request()->input('status'))) {
                $qb->where('products.status', 'active');
            }

            if (isset($params['search'])) {
                $qb->where('products.name', 'like', '%' . urldecode($params['search']) . '%');
            }

            return $qb->groupBy('products.id');
        });

        # apply scope query so we can fetch the raw sql and perform a count
        $repository->applyScope();
        $countQuery = "select count(*) as aggregate from ({$repository->model->toSql()}) c";
        $count = collect(DB::select($countQuery, $repository->model->getBindings()))->pluck('aggregate')->first();

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $repository->scopeQuery(function ($query) use ($page, $perPage) {
                return $query->forPage($page, $perPage);
            });

            # manually build the paginator
            $items = $repository->get();
        } else {
            $items = [];
        }

        return $items;
    }

    public function getProductsByBrandID($type = null)
    {
        $params = request()->input();
        if (!isset($params['brand_id'])) {
            return collect(); // or any other empty collection object that you prefer
        }
        $perPage = isset($params['limit']) && !empty($params['limit']) ? $params['limit'] : 20;


        $page = Paginator::resolveCurrentPage('page');

        $repository = $this->with([
            'images',
        ])->scopeQuery(function ($query) use ($params, $type) {
            $priceFilter =  $params['price'] ?? null;
            $order = $params['order'] ?? 'newest_first';

            $qb = $query
                ->distinct()
                ->select('products.*', DB::raw('IF(products.product_type = "simple" AND products.parent_id IS NULL, IF( products.special_price < products.price AND products.special_price > 0,
                products.special_price,
                products.price), 
                (SELECT MIN(
                    IF(
                        p.special_price < p.price AND p.special_price > 0,
                        p.special_price,
                        p.price
                    )
                    ) FROM products p WHERE p.parent_id = products.id)) AS min_price
                '))
                ->where('brand_id', $params['brand_id'])
                ->whereNull('products.parent_id');
            $qb->when($priceFilter, function ($query) use ($priceFilter) {
                $priceRange = explode(',', $priceFilter);
                if (count($priceRange) > 1) {
                    $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                        ->where('variants.product_type', '=', 'simple')
                        ->where(function ($query) use ($priceRange) {
                            $query->whereBetween('variants.price', $priceRange)
                                ->orWhere(function ($query2) use ($priceRange) {
                                    $query2->where('variants.special_price', '>', '0');
                                    $query2->whereBetween('variants.special_price', $priceRange);
                                });
                        });
                    // dd(count($query->get()));
                } else if (count($priceRange) == 1) {
                    $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                        ->where('variants.product_type', '=', 'simple')
                        ->where(function ($query) use ($priceRange) {
                            $query->where('variants.price', '>=', $priceRange[0])
                                ->orWhere(function ($query2) use ($priceRange) {
                                    $query2->where('variants.special_price', '>', '0');
                                    $query2->where('variants.special_price', '>=', $priceRange[0]);
                                });
                        });
                }
            })
                ->when($order, function ($query) use ($order) {
                    if ($order == 'newest_first') {
                        $query = $query->orderBy('products.created_at',  'desc');
                    } else if ($order == 'low_to_high') {
                        $query->orderBy('min_price');
                    } else if ($order == 'high_to_low') {
                        $query->orderByDesc('min_price');
                    } else {
                        $query->orderBy('products.created_at',  'desc');
                    }
                });

            if (is_null(request()->input('status'))) {
                $qb->where('products.status', 'active');
            }

            if (isset($params['search'])) {
                $qb->where('products.name', 'like', '%' . urldecode($params['search']) . '%');
            }

            return $qb->groupBy('products.id');
        });

        # apply scope query so we can fetch the raw sql and perform a count
        $repository->applyScope();
        $countQuery = "select count(*) as aggregate from ({$repository->model->toSql()}) c";
        $count = collect(DB::select($countQuery, $repository->model->getBindings()))->pluck('aggregate')->first();

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $repository->scopeQuery(function ($query) use ($page, $perPage) {
                return $query->forPage($page, $perPage);
            });

            # manually build the paginator
            $items = $repository->get();
        } else {
            $items = [];
        }

        return $items;
    }

    public function getProductsByStoreID($type = null)
    {
        $params = request()->input();
        if (!isset($params['store_id'])) {
            return collect(); // or any other empty collection object that you prefer
        }
        $perPage = isset($params['limit']) && !empty($params['limit']) ? $params['limit'] : 20;


        $page = Paginator::resolveCurrentPage('page');

        $repository = $this->with([
            'images',
        ])->scopeQuery(function ($query) use ($params, $type) {
            $priceFilter =  $params['price'] ?? null;
            $order = $params['order'] ?? 'newest_first';

            $qb = $query
                ->distinct()
                ->select('products.*', DB::raw('IF(products.product_type = "simple" AND products.parent_id IS NULL, IF( products.special_price < products.price AND products.special_price > 0,
                products.special_price,
                products.price), 
                (SELECT MIN(
                    IF(
                        p.special_price < p.price AND p.special_price > 0,
                        p.special_price,
                        p.price
                    )
                    ) FROM products p WHERE p.parent_id = products.id)) AS min_price
                '))
                ->where('user_id', $params['store_id'])
                // ->orderBy('products.created_at',  'desc')
                ->whereNull('parent_id');
            $qb->when($priceFilter, function ($query) use ($priceFilter) {
                $priceRange = explode(',', $priceFilter);
                if (count($priceRange) > 1) {
                    $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                        ->where('variants.product_type', '=', 'simple')
                        ->where(function ($query) use ($priceRange) {
                            $query->whereBetween('variants.price', $priceRange)
                                ->orWhere(function ($query2) use ($priceRange) {
                                    $query2->where('variants.special_price', '>', '0');
                                    $query2->whereBetween('variants.special_price', $priceRange);
                                });
                        });
                    // dd(count($query->get()));
                } else if (count($priceRange) == 1) {
                    $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                        ->where('variants.product_type', '=', 'simple')
                        ->where(function ($query) use ($priceRange) {
                            $query->where('variants.price', '>=', $priceRange[0])
                                ->orWhere(function ($query2) use ($priceRange) {
                                    $query2->where('variants.special_price', '>', '0');
                                    $query2->where('variants.special_price', '>=', $priceRange[0]);
                                });
                        });
                }
            })
                ->when($order, function ($query) use ($order) {
                    if ($order == 'newest_first') {
                        $query = $query->orderBy('products.created_at',  'desc');
                    } else if ($order == 'low_to_high') {
                        $query->orderBy('min_price');
                    } else if ($order == 'high_to_low') {
                        $query->orderByDesc('min_price');
                    } else {
                        $query->orderBy('products.created_at',  'desc');
                    }
                });
            if (is_null(request()->input('status'))) {
                $qb->where('products.status', 'active');
            }

            if (isset($params['search'])) {
                $qb->where('products.name', 'like', '%' . urldecode($params['search']) . '%');
            }
            return $qb->groupBy('products.id');
        });

        # apply scope query so we can fetch the raw sql and perform a count
        $repository->applyScope();
        $countQuery = "select count(*) as aggregate from ({$repository->model->toSql()}) c";
        $count = collect(DB::select($countQuery, $repository->model->getBindings()))->pluck('aggregate')->first();

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $repository->scopeQuery(function ($query) use ($page, $perPage) {
                return $query->forPage($page, $perPage);
            });

            # manually build the paginator
            $items = $repository->get();
        } else {
            $items = [];
        }

        return $items;
    }

    public function getProductsByCategoryID($type = null)
    {
        $params = request()->input();
        if (!isset($params['category_id'])) {
            return collect(); // or any other empty collection object that you prefer
        }
        $perPage = isset($params['limit']) && !empty($params['limit']) ? $params['limit'] : 20;


        $page = Paginator::resolveCurrentPage('page');

        $repository = $this->with([
            'images',
        ])->scopeQuery(function ($query) use ($params, $type) {
            $priceFilter =  $params['price'] ?? null;
            $order = $params['order'] ?? 'newest_first';

            $qb = $query
                ->distinct()
                ->select('products.*', DB::raw('IF(products.product_type = "simple" AND products.parent_id IS NULL, IF( products.special_price < products.price AND products.special_price > 0,
                products.special_price,
                products.price), 
                (SELECT MIN(
                    IF(
                        p.special_price < p.price AND p.special_price > 0,
                        p.special_price,
                        p.price
                    )
                    ) FROM products p WHERE p.parent_id = products.id)) AS min_price
                '))
                ->where('products.category_id', $params['category_id'])
                // ->orderBy('products.created_at',  'desc')
                ->whereNull('products.parent_id');
            $qb->when($priceFilter, function ($query) use ($priceFilter) {
                $priceRange = explode(',', $priceFilter);
                if (count($priceRange) > 1) {
                    $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                        ->where('variants.product_type', '=', 'simple')
                        ->where(function ($query) use ($priceRange) {
                            $query->whereBetween('variants.price', $priceRange)
                                ->orWhere(function ($query2) use ($priceRange) {
                                    $query2->where('variants.special_price', '>', '0');
                                    $query2->whereBetween('variants.special_price', $priceRange);
                                });
                        });
                    // dd(count($query->get()));
                } else if (count($priceRange) == 1) {
                    $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                        ->where('variants.product_type', '=', 'simple')
                        ->where(function ($query) use ($priceRange) {
                            $query->where('variants.price', '>=', $priceRange[0])
                                ->orWhere(function ($query2) use ($priceRange) {
                                    $query2->where('variants.special_price', '>', '0');
                                    $query2->where('variants.special_price', '>=', $priceRange[0]);
                                });
                        });
                }
            })
                ->when($order, function ($query) use ($order) {
                    if ($order == 'newest_first') {
                        $query = $query->orderBy('products.created_at',  'desc');
                    } else if ($order == 'low_to_high') {
                        $query->orderBy('min_price');
                    } else if ($order == 'high_to_low') {
                        $query->orderByDesc('min_price');
                    } else {
                        $query->orderBy('products.created_at',  'desc');
                    }
                });
            if (is_null(request()->input('status'))) {
                $qb->where('products.status', 'active');
            }

            if (isset($params['search'])) {
                $qb->where('products.name', 'like', '%' . urldecode($params['search']) . '%');
            }

            return $qb->groupBy('products.id');
        });

        # apply scope query so we can fetch the raw sql and perform a count
        $repository->applyScope();
        $countQuery = "select count(*) as aggregate from ({$repository->model->toSql()}) c";
        $count = collect(DB::select($countQuery, $repository->model->getBindings()))->pluck('aggregate')->first();

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $repository->scopeQuery(function ($query) use ($page, $perPage) {
                return $query->forPage($page, $perPage);
            });

            # manually build the paginator
            $items = $repository->get();
        } else {
            $items = [];
        }

        return $items;
    }

    public function globalSearch($type = null)
    {
        $params = request()->input();
        if (!isset($params['search'])) {
            return collect(); // or any other empty collection object that you prefer
        }
        $perPage = isset($params['limit']) && !empty($params['limit']) ? $params['limit'] : 20;


        $page = Paginator::resolveCurrentPage('page');

        $repository = $this->with([
            'images',
        ])->scopeQuery(function ($query) use ($params, $type) {
            $priceFilter =  $params['price'] ?? null;
            $order = $params['order'] ?? 'newest_first';

            $qb = $query
                ->distinct()
                ->select('products.*', DB::raw('IF(products.product_type = "simple" AND products.parent_id IS NULL, IF( products.special_price < products.price AND products.special_price > 0,
                products.special_price,
                products.price), 
                (SELECT MIN(
                    IF(
                        p.special_price < p.price AND p.special_price > 0,
                        p.special_price,
                        p.price
                    )
                    ) FROM products p WHERE p.parent_id = products.id)) AS min_price
                '))
                ->where('products.name', 'like', '%' . urldecode($params['search']) . '%')
                // ->orderBy('products.created_at',  'desc')
                ->whereNull('parent_id');
            $qb->when($priceFilter, function ($query) use ($priceFilter) {
                $priceRange = explode(',', $priceFilter);
                if (count($priceRange) > 1) {
                    $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                        ->where('variants.product_type', '=', 'simple')
                        ->where(function ($query) use ($priceRange) {
                            $query->whereBetween('variants.price', $priceRange)
                                ->orWhere(function ($query2) use ($priceRange) {
                                    $query2->where('variants.special_price', '>', '0');
                                    $query2->whereBetween('variants.special_price', $priceRange);
                                });
                        });
                    // dd(count($query->get()));
                } else if (count($priceRange) == 1) {
                    $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                        ->where('variants.product_type', '=', 'simple')
                        ->where(function ($query) use ($priceRange) {
                            $query->where('variants.price', '>=', $priceRange[0])
                                ->orWhere(function ($query2) use ($priceRange) {
                                    $query2->where('variants.special_price', '>', '0');
                                    $query2->where('variants.special_price', '>=', $priceRange[0]);
                                });
                        });
                }
            })
                ->when($order, function ($query) use ($order) {
                    if ($order == 'newest_first') {
                        $query = $query->orderBy('products.created_at',  'desc');
                    } else if ($order == 'low_to_high') {
                        $query->orderBy('min_price');
                    } else if ($order == 'high_to_low') {
                        $query->orderByDesc('min_price');
                    } else {
                        $query->orderBy('products.created_at',  'desc');
                    }
                });
            if (is_null(request()->input('status'))) {
                $qb->where('products.status', 'active');
            }


            return $qb->groupBy('products.id');
        });

        # apply scope query so we can fetch the raw sql and perform a count
        $repository->applyScope();
        $countQuery = "select count(*) as aggregate from ({$repository->model->toSql()}) c";
        $count = collect(DB::select($countQuery, $repository->model->getBindings()))->pluck('aggregate')->first();

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $repository->scopeQuery(function ($query) use ($page, $perPage) {
                return $query->forPage($page, $perPage);
            });

            # manually build the paginator
            $items = $repository->get();
        } else {
            $items = [];
        }

        return $items;
    }

    public function getMostViewedProducts($type = null)
    {
        $params = request()->input();
        $perPage = isset($params['limit']) && !empty($params['limit']) ? $params['limit'] : 20;

        $page = Paginator::resolveCurrentPage('page');

        $repository = $this->with([
            'images',
        ])->scopeQuery(function ($query) use ($params, $type) {
            $priceFilter =  $params['price'] ?? null;

            $qb = $query
                ->distinct()
                ->select('products.*')
                ->orderBy('products.views',  'desc')
                ->whereNull('parent_id');

            $qb->when($priceFilter, function ($query) use ($priceFilter) {
                $priceRange = explode(',', $priceFilter);
                if (count($priceRange) > 1) {
                    $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                        ->where('variants.product_type', '=', 'simple')
                        ->where(function ($query) use ($priceRange) {
                            $query->whereBetween('variants.price', $priceRange)
                                ->orWhere(function ($query2) use ($priceRange) {
                                    $query2->where('variants.special_price', '>', '0');
                                    $query2->whereBetween('variants.special_price', $priceRange);
                                });
                        });
                    // dd(count($query->get()));
                } else if (count($priceRange) == 1) {
                    $query->leftJoin('products as variants', 'products.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                        ->where('variants.product_type', '=', 'simple')
                        ->where(function ($query) use ($priceRange) {
                            $query->where('variants.price', '>=', $priceRange[0])
                                ->orWhere(function ($query2) use ($priceRange) {
                                    $query2->where('variants.special_price', '>', '0');
                                    $query2->where('variants.special_price', '>=', $priceRange[0]);
                                });
                        });
                }
            });

            if (is_null(request()->input('status'))) {
                $qb->where('products.status', 'active');
            }

            if (isset($params['search'])) {
                $qb->where('products.name', 'like', '%' . urldecode($params['search']) . '%');
            }

            return $qb->groupBy('products.id');
        });

        # apply scope query so we can fetch the raw sql and perform a count
        $repository->applyScope();
        $countQuery = "select count(*) as aggregate from ({$repository->model->toSql()}) c";
        $count = collect(DB::select($countQuery, $repository->model->getBindings()))->pluck('aggregate')->first();

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $repository->scopeQuery(function ($query) use ($page, $perPage) {
                return $query->forPage($page, $perPage);
            });

            # manually build the paginator
            $items = $repository->get();
        } else {
            $items = [];
        }

        return $items;
    }






    public function getFilterHome($type = null)
    {


        $params = request()->input();
        $perPage = isset($params['limit']) && !empty($params['limit']) ? $params['limit'] : 20;


        $page = Paginator::resolveCurrentPage('page');

        $repository = $this->with([
            'images',
        ])->scopeQuery(function ($query) use ($params, $type) {


            $qb = $query
                ->distinct()
                ->select('products.*')
                ->orderBy('products.created_at',  'desc')
                ->where(function ($query) {
                    $query->where('products.countdown', '>=', Carbon::now())
                        ->orWhere('products.countdown', '=', NULL);
                })
                ->whereNull('parent_id');



            if (is_null(request()->input('status'))) {
                $qb->where('products.status', 'active');
            }

            if (isset($params['search'])) {
                $qb->where('products.name', 'like', '%' . urldecode($params['search']) . '%');
            }
            if (isset($params['price'])) {
                if ($priceFilter = request('price')) {
                    $priceRange = explode(',', $priceFilter);

                    if (count($priceRange) > 1) {
                        $qb
                            ->where('products.price', '>=', $priceRange[0])
                            ->where('products.price', '<=', end($priceRange));
                    } else {
                        $qb
                            ->where('products.price', '>=', $priceRange);
                    }
                }
            }

            if (isset($params['owner'])) {
                if (urldecode($params['owner']) == 'store') {
                    $qb->whereHas('user', function ($query) {
                        $query->where('users.type', 2);
                    });
                } else if (urldecode($params['owner']) == 'individual') {
                    $qb->whereHas('user', function ($query) {
                        $query->where('users.type',  1);
                    });
                }
            }


            if (isset($params['order'])) {
                if (urldecode($params['order']) == 'newest_first') {
                    $qb->orderBy('products.created_at',  'desc');
                } else if (urldecode($params['order']) == 'low_to_high') {
                    $qb->orderBy('products.price', 'asc');
                } else if (urldecode($params['order']) == 'high_to_low') {
                    $qb->orderBy('products.price',  'desc');
                }
            } else {
                $qb->orderBy('products.created_at',  'desc');
            }

            if (isset($params['category'])) {
                if ($categoryFilter = request('category')) {
                    $category = explode(',', $categoryFilter);
                    $qb->join('categories', 'categories.id', 'products.category_id')
                        ->join('categories', 'categories.id', 'categories.category_id')
                        ->whereIn('categories.id', $category);
                }
            }

            return $qb->groupBy('products.id');
        });

        # apply scope query so we can fetch the raw sql and perform a count
        $repository->applyScope();
        $countQuery = "select count(*) as aggregate from ({$repository->model->toSql()}) c";
        $count = collect(DB::select($countQuery, $repository->model->getBindings()))->pluck('aggregate')->first();

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $repository->scopeQuery(function ($query) use ($page, $perPage) {
                return $query->forPage($page, $perPage);
            });

            # manually build the paginator
            $items = $repository->get();
        } else {
            $items = [];
        }

        return $items;
    }
}
