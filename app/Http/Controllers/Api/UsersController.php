<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\User;
use App\Profile;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('profile' , 'role')->get();
        return response()->json($users);
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
          'password'=>'required',
        ]);
        $user = new User();
        $user->role_id = $request->role_id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $user_id = $user->id;

        $profile = new Profile();
        $profile->user_id = $user_id;
        $profile->save();

        return response()->json($user);
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
        $user = User::find($id);
        $user->name = $request->name;
        
            $user->role_id = $request->role_id;

       
        $user->save();
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $profile = Profile::where('user_id', $id)->first();
        if(Storage::disk('public')->exists('Users/'.$profile->profile_image)){
          Storage::disk('public')->delete('Users/'.$profile->profile_image);
        } 
        if ($profile) {
           $profile->delete();
        }
        $user->delete();
    }

  public function emailCheck(Request $request){
    $request->validate([
     'email'=>'required|unique:users'
    ]);

    return response()->json(['message'=> 'Valid Email'], 200);
  }

  public function updateRole(Request $request, $id){
    $user  = User::findOrFail($id);
    $user->role_id = $request->role;
    $user->save();
  }


  public function profileImage(Request $request){
      
      $this->validate($request,[
       'photo'=>'required|image'
      ]);
      $userId = $request->id;
      $profile = Profile::where('user_id', $userId)->first();
      $image =  $request->file('photo');
      $slug = str_slug($request->user);
      if ($image) {
          $currentTime = Carbon::now()->toDateString();
          $imageName = $slug.'-'.$currentTime.'-'.uniqid().'.'.$image->getClientOriginalExtension(); 
          if (!Storage::disk('public')->exists('Users')) {
              Storage::disk('public')->makeDirectory('Users');
          }

          if (Storage::disk('public')->exists('Users/'.$profile->profile_image )) {
               Storage::disk('public')->delete('Users/'.$profile->profile_image );
           }

          $resizeImage = Image::make($image)->resize(512, 512)->stream();
          Storage::disk('public')->put('Users/'.$imageName, $resizeImage);
      }else{
        $imageName = "default.png";
      }

      $profile->profile_image = $imageName;
      $profile->save();

      return response()->json($profile);



  }

}
