<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function listBrand(Request $request)
    {


        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;

        $listBrands = Brand::orderBy('created_at', 'desc')
            ->where(function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('created_at', 'like', '%' . $search . '%');
            })->paginate($perPage);
        $listBrands->appends(request()->query());
        return view('admin.brand.listBrand', compact('listBrands', 'sortColumn', 'sortDirection'));
    }

    public function addNewBrand(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image_path' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $Brand = new Brand();

            $Brand->name = $request->name;


            // $Brand = Brand::latest()->take(1)->first();
            if ($request->hasFile('image_path')) {

                $img = $request->image_path;
                $uploadFile1 = $img->store('brand_images');
            } else {
                $size1 = '';
                $uploadFile1 = '';
            }

            $Brand->image_path = $uploadFile1;

            $Brand->save();


            return response()->json([
                'status' => 200,
                'Brand' => $Brand,
                'message' => 'Brand Added Successfully',
            ]);
        }
    }

    public function is_active(Request $request, $id)
    {

        $Brand = Brand::findOrFail($id);

        if ($Brand) {
            $Brand->is_active = 0;
        }

        $Brand->save();

        return response()->json([
            'status' => 200,
            'message' => 'Brand Inactivate Successfully',
        ]);
    }

    public function is_inactive(Request $request, $id)
    {

        $Brand = Brand::findOrFail($id);

        if ($Brand) {
            $Brand->is_active = 1;
        }

        $Brand->save();

        return response()->json([
            'status' => 200,
            'message' => 'Brand Activate Successfully',
        ]);
    }


    public function editBrand(Request $request, $id)
    {

        $Brand = Brand::find($id);

        if ($Brand) {

            return response()->json([
                'Brand' => $Brand
            ]);
        }
    }



    public function updateBrand(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $Brand = Brand::find($id);

            if ($Brand) {

                if ($request->hasFile('image')) {

                    $img = $request->image_path;
                    $uploadFile1 = $img->store('Brand_images');
                } else {
                    $size1 = '';
                    $uploadFile1 = $Brand->image;
                }

                $Brand->image_path = $uploadFile1;
                $Brand->name = $request->name;
                $Brand->save();

                return response()->json([
                    'status' => 200,
                    'Brand' => $Brand,
                    'message' => 'Brand Added Successfully',
                ]);
            }
        }
    }
}
