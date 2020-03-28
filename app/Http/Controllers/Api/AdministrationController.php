<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\User;
use App\Profile;
use App\Role;

class AdministrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        
        $administration = User::with('role', 'profile')->where('role_id', 1)->orWhere('role_id', 2)->get();
         return response()->json($administration);
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
          'name'=>'required',
          'email'=>'required | unique:users',
          'selectRole'=>'required',
        ]);
      
       
       $image = $request->image;
       $slug = str_slug($request->name);
       if($image ){
           $currenTime = Carbon::now()->toDateString();
           $position = strpos($image, ';');
           $sub = substr($image, 0, $position);
           $ex = explode('/', $sub)[1];
           $imageName =$slug.'-'.$currenTime.'-'.uniqid().'.'.$ex;
           if (!Storage::disk('public')->exists('Users')) {
            Storage::disk('public')->makeDirectory('Users');
        }

        
          $resizeImage = Image::make($image)->resize(512, 512)->stream();
          Storage::disk('public')->put('Users/'.$imageName, $resizeImage);

       }else{
           $imageName = 'default.png';
          
       }
       $admin = new User();
       $admin->name = $request->name;
       $admin->email = $request->email;
       $admin->password = bcrypt($request->password);
       $admin->role_id = $request->selectRole;
       $admin->save();
       $admin_id = $admin->id;
       $profile = new Profile();
       $profile->user_id = $admin_id;
       $profile->profile_image = $imageName;
       $profile->save();

       $administration = User::with('role', 'profile')->where('role_id', 1)->orWhere('role_id', 2)->get();
         return response()->json($administration);
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
       $admin = User::findOrFail($id);
       $profile = Profile::where('user_id', $id)->first();
       $image = $request->image;
       $slug = str_slug($request->name);
       if($image ){
           $currenTime = Carbon::now()->toDateString();
           $position = strpos($image, ';');
           $sub = substr($image, 0, $position);
           $ex = explode('/', $sub)[1];
           $imageName =$slug.'-'.$currenTime.'-'.uniqid().'.'.$ex;
           if (!Storage::disk('public')->exists('Users')) {
            Storage::disk('public')->makeDirectory('Users');
        }

        if (Storage::disk('public')->exists('Users/'.$admin->profile->profile_image )) {
             Storage::disk('public')->delete('Users/'.$admin->profile->profile_image );
         }
          $resizeImage = Image::make($image)->resize(512, 512)->stream();
          Storage::disk('public')->put('Users/'.$imageName, $resizeImage);
       }else{
           $imageName = $profile->profile_image;
          
       }

       $admin->name = $request->name;
       $profile->profile_image = $imageName;
       if($request->selectRole != null){
        $admin->role_id = $request->selectRole;
       }
       $admin->save();
       $profile->save();
       
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $administration = User::findOrFail($id);
       $profile = Profile::where('user_id', $id)->first();
       if(Storage::disk('public')->exists('Users/'.$administration->profile->profile_image)){
          Storage::disk('public')->delete('Users/'.$administration->profile->profile_image);
        } 
        if ($profile) {
            $profile->delete();
        }
        $administration->delete();
    }
}
