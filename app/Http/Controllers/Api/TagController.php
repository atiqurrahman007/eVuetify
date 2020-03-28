<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tag;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return response()->json(['tag'=>$tags], 200);
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
          'name'=>'required'
        ]);

        $tag = new Tag();

        $tag->name =  $request->name;
        $tag->slug =  str_slug($request->name);

        if ($request->status == true) {
           $tag->status = true ; 
        }else{
           $tag->status = false ; 
        }

        $tag->save();
        return response()->json(['tag'=>$tag], 200);
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
          'name'=>'required'
        ]);

        $tag = Tag::find($id);

        $tag->name =  $request->name;
        $tag->slug =  str_slug($request->name);

        if ($request->status == true) {
           $tag->status = true ; 
        }else{
           $tag->status = false ; 
        }

        $tag->save();
        return response()->json(['tag'=>$tag], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        $tag->delete();
        return response()->json($tag);
    }


    // Status update
    public function status($id){
        $tag = Tag::findOrFail($id);
        if ($tag->status == true) {
           $tag->status = false ; 
        }else if($tag->status == false){
           $tag->status = true ; 
        }

        $tag->save();
        return response()->json(['tag'=>$tag], 200);
    }

    public function deleteAll(Request $request){

        $tag = Tag::whereIn('id', $request->tags)->delete();
        return response()->json($tag);

    }
}
