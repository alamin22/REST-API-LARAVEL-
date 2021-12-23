<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class passportAuthController extends Controller
{
    public function userRegistration(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8',
        ]);
        $user= User::create([
            'name' =>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);
        //data get here
        $datas['name'] = $user->name;
        $datas['email'] = $user->email;
        $datas['token']= $user->createToken('MyAuthApp')->accessToken;
        //return the access token 
        return response()->json(['status' => 'Registration Successfully','data'=>$datas], 200);
    }
    //login user
    public function userLogin(Request $request){
        $login_credentials=[
            'email'=>$request->email,
            'password'=>$request->password,
        ];
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $authUser = Auth::user(); 
            $data['token'] =  $authUser->createToken('MyAuthApp')->accessToken; 
            $data['name'] =  $authUser->name;
            return response()->json(['status'=>'Authorized','data'=>$data], 200);
        } 
        else{
            return response()->json(['error' => 'UnAuthorised Access'], 401);
        }
    }
    //product data retrieve
    public function authorizedUserDetails(){
        //returns details
        $product = Product::get();
        return response()->json(['user' => 'Authorized User','data' => $product], 200);
    }
    //function for logout
    public function logout(Request $request)
    {        
       $token = $request->user()->token();
       $token->revoke();
       $response = ['status' => 'Logout Successfully'];
       return response()->json($response,200);
    }
}
