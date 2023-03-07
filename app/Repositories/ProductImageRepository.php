<?php

namespace App\Repositories;

use Illuminate\Container\Container;
use App\Repositories\AttributeRepository;
use App\Eloquent\Repository;
use App\Models\ProductAttributeValue;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductImageRepository extends Repository
{

    /**
     * Create a new repository instance.
     *
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
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
        return 'App\Models\ProductImage';
    }

    /**
     * Upload.
     *
     * @param  array  $data
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function uploadImages($data, $product): void
    {
        /**
         * Previous model ids for filtering.
         */
        $previousIds = $product->images()->pluck('id');
        if (
            isset($data['images'])
            && $data['images']
        ) {
            foreach ($data['images'] as $fileOrModelId) {
                if ($fileOrModelId instanceof UploadedFile) {
                    $this->create([
                        'product_image' => $fileOrModelId->store($this->getProductDirectory($product)),
                        'product_id'    => $product->id,
                        'sort'          => null,
                    ]);
                } else {

                    if (is_numeric($index = $previousIds->search($fileOrModelId))) {
                        $previousIds->forget($index);
                    }
                }
            }
        }

        foreach ($previousIds as $indexOrModelId) {
            if ($model = $this->find($indexOrModelId)) {
                Storage::delete($model->product_image);

                $this->delete($indexOrModelId);
            }
        }
    }

    /**
     * Get product directory.
     *
     * @param  \App\Models\Product $product
     * @return string
     */
    public function getProductDirectory($product): string
    {
        return 'product/' . $product->id;
    }
}
