<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\BannerImage;
use Illuminate\Http\Request;

class BannerImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function fileCreate($id)
    {
        $bannerImage = BannerImage::where('banner_id', $id)->get();
        $banner = Banner::findOrFail($id);
        return view('admin.banner.bannerImage', compact('bannerImage', 'banner'));
    }

    public function fileStore(Request $request, $id)
    {

        $sliderCount = BannerImage::where('banner_id',$id)->count();


        if($id== 2 && $sliderCount <2){

        if ($request->hasFile('file')) {

            $img = $request->file('file');
            $uploadFile1 = $img->store('banner_images');


            // $banner = BannerImage::latest()->take(1)->first();
            // $img = $request->image;
            // $uploadFile1 = $img->store('banner_images/' . $banner->id);

            $imagebanner = new bannerImage();
            $imagebanner->banner_id = $id;
            $imagebanner->image = $uploadFile1;
            $imagebanner->save();
            return response()->json(['success' => $uploadFile1]);

        } else {
            $size1 = '';
            $uploadFile1 = '';
        }
    }

    else if($id == 1){
        if ($request->hasFile('file')) {

            $img = $request->file('file');
            $uploadFile1 = $img->store('banner_images');


            // $banner = BannerImage::latest()->take(1)->first();
            // $img = $request->image;
            // $uploadFile1 = $img->store('banner_images/' . $banner->id);

            $imagebanner = new bannerImage();
            $imagebanner->banner_id = $id;
            $imagebanner->image = $uploadFile1;
            $imagebanner->save();
            return response()->json(['success' => $uploadFile1]);

        } else {
            $size1 = '';
            $uploadFile1 = '';
        }
    }

    }

    // public function fileDestroy(Request $request)
    // {
    //     $filename = $request->get('filename');
    //     $path = '/admin/assets/img/banner_image/'.$filename;
    //     bannerImage::where('banner_image', $path)->delete();

    //     if (file_exists($path)) {
    //         unlink($path);
    //     }
    //     return $filename;
    // }

    public function fileDelete($id)
    {
        $bannerImage = bannerImage::findOrFail($id);
        $bannerImage->delete();
        return redirect()->back();

    }

}
