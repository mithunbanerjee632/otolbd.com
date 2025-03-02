<?php

namespace App\Http\Controllers;
use App\Models\Brands;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(){
      return view('admin.index');
    }

    //for Brands

    public function brands(){
       $brands = Brands::orderBy('id','DESC')->paginate(10);
      return view('admin.brands',compact('brands'));
    }

    public function brands_ad(){
      return view('admin.brand-add');
    }

    public function brand_store(Request $request){

      $request->validate([
        'name'=>'required',
        'slug'=>'required|unique:brands,slug',
        'image'=>'required|mimes:png,jpg,jpeg|max:2048'
      ]);

      $brands = new Brands();

      $brands->name = $request->name;
      $brands->slug = Str::slug($request->name);
      $image = $request->file('image');
      $file_extension = $request->file('image')->extension();
      $file_name = Carbon::now()->timestamp.'.'.$file_extension;
      $this->GenerateBrandThumbnailImage($image,$file_name);
      $brands->image = $file_name;
      $brands->save();
      return redirect()->route('admin.brands')->with('status','Brand has been added Successfully!');


    }

    public function GenerateBrandThumbnailImage($image,$imageName){
    //   $destination_path= public_path('uploads/brands');
    //   $img = Image::read($image->path);
    //   $img->cover(124,124,"top");
    //   $img->resize(124,124,function($constraint){
    //      $constraint->aspectRatio();
    //   })->save($destination_path.'/'.$imageName);

    $destinationPath = public_path('uploads/brands');
    $img = Image::read($image->getRealPath());
    $img->cover(124, 124, "top");
    $img->resize(124, 124, function ($constraint){
    $constraint->aspectRatio();
    })->save($destinationPath.'/'.$imageName);
    }
}
