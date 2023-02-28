<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategorieController extends Controller
{
    public function listCategory(Request $request)
    {


        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;

        $listCategorie = Categorie::orderBy('created_at', 'desc')
            ->where(function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('created_at', 'like', '%' . $search . '%');
            })->paginate($perPage);
        $listCategorie->appends(request()->query());
        return view('admin.category.listCategory', compact('listCategorie', 'sortColumn', 'sortDirection'));
    }

    public function addNewCategory(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $category = new Categorie();

            $category->name = $request->name;


        $categorie = Categorie::latest()->take(1)->first();
            if ($request->hasFile('image')) {

                $img = $request->image;
                $uploadFile1 = $img->store('category_images/' . $categorie->id);
            } else {
                $size1 = '';
                $uploadFile1 = '';
            }

            $category->image = $uploadFile1;

            $category->save();

            dd($category);

            return response()->json([
                'status' => 200,
                'category' => $category,
                'message' => 'Category Added Successfully',
            ]);
        }
    }
}
