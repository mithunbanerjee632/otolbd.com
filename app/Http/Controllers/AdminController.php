<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Models\Brands;
use App\Models\Category;
use App\Models\Product;
//use Intervention\Image\Image;

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
            'slug'=>'required|unique:categories,slug,'.$request->id.',id',
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

    //products

    public function products(){
        $products = Product::orderBy('created_at','desc')->paginate(10);
        return view('admin.products',compact('products'));
    }

    public function products_add(){
        $categories = Category::select('id','name')->orderBy('name','desc')->get();
        $brands = Brands::select('id','name')->orderBy('name','desc')->get();
        return view('admin.product_add',compact('categories','brands'));
    }

    public function products_store(Request $request){
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:products,slug',
            'category_id'=>'required',
            'brand_id'=>'required',
            'short_description'=>'required',
            'description'=>'required',
            'regular_price'=>'required',
            'sale_price'=>'required',
            'SKU'=>'required',
            'stock_status'=>'required',
            'featured'=>'required',
            'quantity'=>'required',
            'image'=>'required|mimes:png,jpg,jpeg|max:2048'
        ]);
        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $current_timestamp = Carbon::now()->timestamp;
        if($request->hasFile('image'))
        {

            $image = $request->file('image');
            $imageName = $current_timestamp.'.'.$image->extension();
            $this->GenerateProductThumbnailImage($image,$imageName);
            $product->image = $imageName;
        }
        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;
        if($request->hasFile('images'))
        {

            $allowedfileExtension=['jpg','png','jpeg'];
            $files = $request->file('images');
            foreach($files as $file){
                $gextension = $file->getClientOriginalExtension();
                $check=in_array($gextension,$allowedfileExtension);
                if($check)
                {
                    $gfilename = $current_timestamp . "-" . $counter . "." . $gextension;
                    $this->GenerateProductThumbnailImage($file,$gfilename);
                    array_push($gallery_arr,$gfilename);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->save();
        return redirect()->route('admin.products')->with('status','Record has been added successfully !');
    }





        public function product_edit($id){
          $product = Product::find($id);
          $categories = Category::select('id','name')->orderBy('name','desc')->get();

          $brands = Brands::select('id','name')->orderBy('name','desc')->get();

          return view('admin.product_edit',compact('product','categories','brands'));



        }

        public function product_update(Request $request){
            //dd($request->all());
            $request->validate([
                'name'=>'required',
                'slug'=>'required|unique:products,slug,'.$request->id.',id',
                'category_id'=>'required',
                'brand_id'=>'required',
                'short_description'=>'required',
                'description'=>'required',
                'regular_price'=>'required',
                'sale_price'=>'required',
                'SKU'=>'required',
                'stock_status'=>'required',
                'featured'=>'required',
                'quantity'=>'required',
                'image'=>'mimes:png,jpg,jpeg|max:2048'
            ]);
            $product = new Product();
            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->short_description = $request->short_description;
            $product->description = $request->description;
            $product->regular_price = $request->regular_price;
            $product->sale_price = $request->sale_price;
            $product->SKU = $request->SKU;
            $product->stock_status = $request->stock_status;
            $product->featured = $request->featured;
            $product->quantity = $request->quantity;
            $current_timestamp = Carbon::now()->timestamp;
            if($request->hasFile('image'))
            {
                if (File::exists(public_path('uploads/products').'/'.$product->image)) {
                    File::delete(public_path('uploads/products').'/'.$product->image);
                }
                if (File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)) {
                    File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
                }

                $image = $request->file('image');
                $imageName = $current_timestamp.'.'.$image->extension();
                $this->GenerateProductThumbnailImage($image,$imageName);
                $product->image = $imageName;
            }
            $gallery_arr = array();
            $gallery_images = "";
            $counter = 1;
            if($request->hasFile('images'))
            {
                $oldGImages = explode(",",$product->images);
                foreach($oldGImages as $gimage)
                {
                    if (File::exists(public_path('uploads/products').'/'.trim($gimage))) {
                        File::delete(public_path('uploads/products').'/'.trim($gimage));
                    }
                    if (File::exists(public_path('uploads/products/thumbails').'/'.trim($gimage))) {
                        File::delete(public_path('uploads/products/thumbails').'/'.trim($gimage));
                    }
                }


                $allowedfileExtension=['jpg','png','jpeg'];
                $files = $request->file('images');
                foreach($files as $file){
                    $gextension = $file->getClientOriginalExtension();
                    $check=in_array($gextension,$allowedfileExtension);
                    if($check)
                    {
                        $gfilename = $current_timestamp . "-" . $counter . "." . $gextension;
                        $this->GenerateProductThumbnailImage($file,$gfilename);
                        array_push($gallery_arr,$gfilename);
                        $counter = $counter + 1;
                    }
                }
                $gallery_images = implode(',', $gallery_arr);
                $product->images = $gallery_images;
            }

            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->save();
            return redirect()->route('admin.products')->with('status','Record has been updated successfully !');
        }


        public function GenerateProductThumbnailImage($image,$imageName){

            $destinationThumbnailPath = public_path('uploads/products/thumbnails');
            $destinationPath = public_path('uploads/products');
            $img = Image::read($image->path());
            $img->cover(540, 689, "top");

            $img->resize(540, 689,function ($constraint){
            $constraint->aspectRatio();
            })->save($destinationPath.'/'.$imageName);

            $img->resize(104, 104,function ($constraint){
                $constraint->aspectRatio();
                })->save($destinationThumbnailPath.'/'.$imageName);

            }


}
