<?php

namespace App\Http\Controllers;
use App\Models\Brands;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

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
         //dd($request->all());
      $request->validate([
        'name'=>'required',
        'slug'=>'required|unique:brands,slug',
        'image'=>'required|image'
      ]);

      $brands = new Brands();

      $brands->name = $request->name;

      $brands->slug = Str::slug($request->name);
      $file = $request->file('image');

    //   $file_extension = $request->file('image')->extension();
    //   $file_name = Carbon::now()->timestamp.'.'.$file_extension;
    //   $this->GenerateBrandThumbnailImage($image,$file_name);
    //   $brands->image = $file_name;

      $name = $file->getClientOriginalName();

      $upload_path = 'uploads/brands/';
      $file->move($upload_path,$name);
      $db_path = str_replace('public','',$upload_path);
      $image_url = $db_path.$name;


      $brands->image=$image_url;
      $brands->save();
      return redirect()->route('admin.brands')->with('status','Brand has been added Successfully!');


    }


    public function brands_edit($id){
        $brands = Brands::find($id);
        return view('admin.brands_edit',compact('brands'));
    }

    public function brands_update(Request $request){
        //dd($request->all());
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:brands,slug,'.$request->id.',id',
            'image'=>'required|mimes:png,jpg,jpeg,gif|max:2048'
          ]);


          $brands = Brands::find($request->id);


          $brands->name = $request->name;

          $brands->slug = Str::slug($request->name);

        //   if($request->hasFile('image')){
        //        if(File::exists(public_path('uploads/brands').'/'.$request->image)){
        //           File::delete(public_path('uploads/brands').'/'.$request->image);
        //        }

        //        $image = $request->file('image');
        //        $file_extension = $request->file('image')->extension();
        //        $file_name = Carbon::now()->timestamp.'.'.$file_extension;
        //        $this->GenerateBrandThumbnailImage($image,$file_name);
        //        $brands->image = $file_name;
        //   }

         $update_image= $request->file('image');
         if($update_image){
            $file= $request->file('image');
            $name = $file->getClientOriginalName();
            $upload_path = 'uploads/brands/';;
            File::delete(public_path(). '/'. $brands->image);
            $file->move($upload_path,$name);
            $db_path = str_replace('public','',$upload_path);
            $image_url = $db_path.$name;
         }else{
            $image_url = $brands->image;
         }

         $brands->image = $image_url;

          $brands->save();
          return redirect()->route('admin.brands')->with('status','Brand has been Updated Successfully!');


    }

    public function brands_delete($id){
        $brand = Brands::find($id);
        if(File::exists(public_path().'/'.$brand->image)){

            File::delete(public_path(). '/'. $brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('stauts','Brand Has Been Deleted Successfully!');
    }

    public function GenerateBrandThumbnailImage($image,$imageName){


    $destinationPath = public_path('uploads/brands');
    $img = Image::read($image->getRealPath());
    $img->cover(124, 124, "top");
    $img->resize(124, 124, function ($constraint){
    $constraint->aspectRatio();
    })->save($destinationPath.'/'.$imageName);
    }



    //for Category

    public function categories(){
       $categories = Category::orderBy('id','desc')->paginate(10);
       return view('admin.category',compact('categories'));
    }

    public function categoriess_ad(){
        return view('admin.category_add');
      }

    public function category_store(Request $request){

        //dd($request->all());
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:categories,slug',
            'image'=>'required|image'
          ]);

          $categories = new Category();

          $categories->name = $request->name;

          $categories->slug = Str::slug($request->name);
          $file = $request->file('image');

        //   $file_extension = $request->file('image')->extension();
        //   $file_name = Carbon::now()->timestamp.'.'.$file_extension;
        //   $this->GenerateBrandThumbnailImage($image,$file_name);
        //   $brands->image = $file_name;

          $name = $file->getClientOriginalName();

          $upload_path = 'uploads/categories/';
          $file->move($upload_path,$name);
          $db_path = str_replace('public','',$upload_path);
          $image_url = $db_path.$name;


          $categories->image=$image_url;
          $categories->save();
          return redirect()->route('admin.categories')->with('status','Category has been added Successfully!');
    }

    public function category_edit($id){
        $categories = Category::find($id);
        return view('admin.category_edit',compact('categories'));

    }

    public function categories_update(Request $request){
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:categories,slug',
            'image'=>'required|image'
          ]);

          $categories = Category::find($request->id);

          $categories->name = $request->name;

          $categories->slug = Str::slug($request->name);
          $file = $request->file('image');

        //   $file_extension = $request->file('image')->extension();
        //   $file_name = Carbon::now()->timestamp.'.'.$file_extension;
        //   $this->GenerateBrandThumbnailImage($image,$file_name);
        //   $brands->image = $file_name;
            if($file->isValid()){
            $name = $file->getClientOriginalName();

            $upload_path = 'uploads/categories/';
            $file->move($upload_path,$name);
            $db_path = str_replace('public','',$upload_path);
            $image_url = $db_path.$name;

            }else{
               $image_url = $categories->image;
            }


          $categories->image=$image_url;
          $categories->save();
          return redirect()->route('admin.categories')->with('status','Category has been Updated Successfully!');
    }


    public function categories_delete($id){
        $categories = Category::find($id);

        if(File::exists(public_path().'/'.$categories->image)){
            File::delete(public_path().'/'.$categories->image);
        }

        $categories->delete();

        return redirect()->route('admin.categories')->with('status','Category has been Deleted Successfully!');

    }


}
