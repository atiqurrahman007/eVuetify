<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::all();
        return response()->json(['categories'=>$category], 200);
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
            'name'=> 'required',
            
        ]);
        $image = $request->file('image');
        $slug = str_slug($request->name);
        if($image){
            $currentTime = Carbon::now()->toDateString();
            $imgName = $slug.'-'.$currentTime.'-'.uniqid().'.'.$image->getClientOriginalExtension();
            if(!Storage::disk('public')->exists('Category')){
                Storage::disk('public')->makeDirectory('Category');
            }
            $imageSize = Image::make($image)->resize(400, 400)->stream();
            Storage::disk('public')->put('Category/'.$imgName, $imageSize);
        }else{
            $imgName = "default.png";
        }
        $category = new Category();
        $category->cat_name = $request->name;
        $category->cat_slug = $slug;
        $category->cat_link = $request->link;
        $category->cat_image = $imgName;
        if ($request->status == true) {
            $category->cat_status = true;
        }else{
            $category->cat_status = false;
        }
        $category->save();
        return response()->json($category);
        

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
         $category = Category::findOrFail($id); 
         $image = $request->cat_image;
        $slug = str_slug($request->cat_name);
        if($image != $category->cat_image){
            $position = strpos($image, ';');
            $sub = substr($image, 0, $position);
            $ex = explode('/', $sub)[1];
            $currentTime = Carbon::now()->toDateString();
            $imgName = $slug.'-'.$currentTime.'-'.uniqid().'.'.$ex;
            if(!Storage::disk('public')->exists('Category')){
                Storage::disk('public')->makeDirectory('Category');
            }
            
            if(Storage::disk('public')->exists('Category/'.$category->cat_image)){
                Storage::disk('public')->delete('Category/'.$category->cat_image);
            }

            $imageSize = Image::make($image)->resize(400, 400)->stream();
            Storage::disk('public')->put('Category/'.$imgName, $imageSize);
        }else{
            $imgName = $category->cat_image;
        }
        
        $category->cat_name = $request->cat_name;
        $category->cat_slug = $slug;
        $category->cat_link = $request->cat_link;
        $category->cat_image = $imgName;
        if ($request->cat_status == true) {
            $category->cat_status = true;
        }else{
            $category->cat_status = false;
        }
        $category->save();
        return response()->json($category);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
           $category = Category::findOrFail($id);
        
            if(Storage::disk('public')->exists('Category/'.$category->cat_image)){
                Storage::disk('public')->delete('Category/'.$category->cat_image);
            } 
            $category->delete();
        
    }

    public function updateStatus(Request $request, $id){
       $cat_status = Category::findOrFail($id);
       if ($cat_status->cat_status == true) {
           $cat_status->cat_status = false;
       }else if($cat_status->cat_status == false){
        $cat_status->cat_status = true; 
       }
       $cat_status->save();
       return response()->json($cat_status);
    }

    public function deleteAll(Request $request){
        
        $categories  = Category::whereIn('id', $request->categories)->get();
      
        foreach ($categories as  $cat) {
            if(Storage::disk('public')->exists('Category/'.$cat->cat_image)){
                Storage::disk('public')->delete('Category/'.$cat->cat_image);
            } 
            $cat->delete();
        }
       
        
    }
}
