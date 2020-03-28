<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Brand;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brand = Brand::all();
        return response()->json(['brands'=>$brand], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
         'brand_name'=>'required'
        ]);

        $image  = $request->brand_image;
        $slug = str_slug($request->brand_name);
        if ($image) {
            
            $position = strpos($image, ';');
            $sub = substr($image, 0, $position);
            $ext = explode('/', $sub)[1];

            $currentTime = Carbon::now()->toDateString();
            $imageName = $slug.'-'.$currentTime.'-'.uniqid().'.'.$ext;

           if (!Storage::disk('public')->exists('Brand')) {
              Storage::disk('public')->makeDirectory('Brand');
           }

           $imageSize  = Image::make($image)->resize(500, 500)->stream();
           Storage::disk('public')->put('Brand/'.$imageName, $imageSize);
        }else{
            $imageName = 'default.png';

        }

        $brand = new Brand();
        $brand->brand_name = $request->brand_name;
        $brand->brand_image = $imageName;
        $brand->brnad_description = $request->brnad_description;
        $brand->brand_slug = $slug;
        
        if ($request->brand_status == true) {
           $brand->brand_status = true;
        }else{
            $brand->brand_status = false;
        }
        $brand->save();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
         'brand_name'=>'required'
        ]);
        $brand = Brand::findOrFail($id);
        $image  = $request->brand_image;
        $slug = str_slug($request->brand_name);
        if ($image != $brand->brand_image) {
            
            $position = strpos($image, ';');
            $sub = substr($image, 0, $position);
            $ext = explode('/', $sub)[1];

            $currentTime = Carbon::now()->toDateString();
            $imageName = $slug.'-'.$currentTime.'-'.uniqid().'.'.$ext;

           if (!Storage::disk('public')->exists('Brand')) {
              Storage::disk('public')->makeDirectory('Brand');
           }

           if (Storage::disk('public')->exists('Brand/'.$brand->brand_image)) {
               Storage::disk('public')->delete('Brand/'.$brand->brand_image);
           }

           $imageSize  = Image::make($image)->resize(500, 500)->stream();
           Storage::disk('public')->put('Brand/'.$imageName, $imageSize);
        }else{
            $imageName = $brand->brand_image;
        }

        
        $brand->brand_name = $request->brand_name;
        $brand->brand_image = $imageName;
        $brand->brnad_description = $request->brnad_description;
        $brand->brand_slug = $slug;
        
        if ($request->brand_status == true) {
           $brand->brand_status = true;
        }else{
            $brand->brand_status = false;
        }
        $brand->save();

        return response()->json($brand);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

         if (Storage::disk('public')->exists('Brand/'.$brand->brand_image)) {
               Storage::disk('public')->delete('Brand/'.$brand->brand_image);
           }
           $brand->delete();
    }

    public function status(Request $request, $id){

        $brand = Brand::findOrFail($id);

        if ($brand->brand_status == true) {
            $brand->brand_status = false;
        }elseif ($brand->brand_status == false) {
            $brand->brand_status = true;
        }

        $brand->save();
    }

    public function deleteMultiple(Request $request){
       
       $brands = Brand::whereIn('id', $request->brands)->get();

       foreach ($brands as $brand) {
           if (Storage::disk('public')->exists('Brand/'.$brand->brand_image)) {
               Storage::disk('public')->delete('Brand/'.$brand->brand_image);
           }

         $brand->delete();  
       }


    }


}
