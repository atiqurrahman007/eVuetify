<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Dailyproduct;

class DailyproductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['auth:api'], ['except' => ['index']]);
    }


    public function index()
    {
       $dailyproduct = Dailyproduct::all();
       return response()->json(['dailyproduct' => $dailyproduct], 200);
      
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
            'user'=>'required',
            'category'=>'required',
            'name'=>'required',
            'code'=>'required',
            'desc'=>'required',
            'buy_price' =>'required',
            'sell_price'=>'required',
            'buy_date' =>'required',
            'exp_date' => 'required',
            'unit' => 'required',
            'quantity' => 'required',
            'unit' => 'required',
        ]);
        
        $image =  $request->image;
        $slug = str_slug($request->name);
        if ($image) {
            $position = strpos($image, ';');
            $sub = substr($image, 0, $position);
            $ext = explode('/', $sub)[1];
            $currentTime = Carbon::now()->toDateString();
            $imageName = $slug.'-'.$currentTime.'-'.uniqid().'.'.$ext;
            if (!Storage::disk('public')->exists('Dailyproducts')) {
                Storage::disk('public')->makeDirectory('Dailyproducts');
            }
            $imageSize = Image::make($image)->resize(1200, 650)->stream();
            Storage::disk('public')->put('Dailyproducts/'.$imageName, $imageSize);
        }else{
            $imageName = 'default.png';
        }

        $dailyProduct = new Dailyproduct();
        $dailyProduct->cat_id = $request->category;
        $dailyProduct->supplier_id = $request->user;
        $dailyProduct->product_code = $request->code;
        $dailyProduct->product_name = $request->name;
        $dailyProduct->product_short_desc = $request->desc;
        $dailyProduct->buying_price = $request->buy_price;
        $dailyProduct->selling_price = $request->sell_price;
        $dailyProduct->buying_date = $request->buy_date;
        $dailyProduct->date_expired = $request->exp_date;
        $dailyProduct->product_image = $imageName;
        $dailyProduct->product_quantity = $request->quantity;
        $dailyProduct->product_unit = $request->unit;
        $dailyProduct->color = $request->color;
        
        if ($request->status == true) {
            $dailyProduct->product_status = true;
        }else{
            $dailyProduct->product_status = false;
        }

        $dailyProduct->save();
        return response()->json($dailyProduct);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
