<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImages;
use Illuminate\Http\Request;

class ProductImagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function fileCreate($id)
    {
        $ProductImages = ProductImages::where('product_id', $id)->get();
        $product = Product::findOrFail($id);
        return view('admin.product.productImages.listProductImages', compact('ProductImages', 'product'));
    }

    public function fileStore(Request $request, $id)
    {
        if ($request->hasFile('file')) {

            $img = $request->file('file');

            $fileName1 = time() . '.' . $img->extension();
            $img->move(public_path('resources/assets/images/product_images/'), $fileName1);
            $uploadFile1 = 'resources/assets/images/product_images/' . $fileName1;

            $imageproduct = new ProductImages();
            $imageproduct->product_id = $id;
            $imageproduct->product_image = $uploadFile1;
            $imageproduct->save();
            return response()->json(['success' => $uploadFile1]);

        } else {
            $size1 = '';
            $uploadFile1 = '';
        }

    }

    // public function fileDestroy(Request $request)
    // {
    //     $filename = $request->get('filename');
    //     $path = '/admin/assets/img/product_image/'.$filename;
    //     ProductImages::where('product_image', $path)->delete();

    //     if (file_exists($path)) {
    //         unlink($path);
    //     }
    //     return $filename;
    // }

    public function fileDelete($id)
    {
        $productImage = ProductImages::findOrFail($id);
        $productImage->delete();
        return redirect()->back();

    }

}
