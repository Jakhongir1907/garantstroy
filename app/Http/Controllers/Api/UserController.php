<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReturnResponseResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('is_admin',0)->get();
        return response()->json([
            'message' => "Bu brigadirlar ro'yhati" ,
            'data' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required' , 'string'],
            'username' => ['required' , 'string' , "unique:users"],
            'password' => ['required'] ,
        ]);

        $user = User::create([
            'name' => $request->name ,
            'username' => $request->username,
            'password' => Hash::make($request->password) ,
            'parol' => $request->password
        ]);
        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if(!$user){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => "Record not found!"
            ]);
        }
        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required' , 'string'],
            'username' => ['required' , 'string' , Rule::unique('users')->ignore($id)],
            'password' => ['required'] ,
        ]);

        $user = User::find($id);
        if(!$user){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => "Record not found!"
            ]);
        }
        $user->update([
            'name' => $request->name ,
            'username' => $request->username,
            'password' => Hash::make($request->password) ,
            'parol' => $request->password
        ]);
        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if(!$user){
            return new ReturnResponseResource([
                'code' => 404 ,
                'message' => "Record not found!"
            ] , 404);
        }
        if($user->expenses->count()>0){
            return new ReturnResponseResource([
                'code' => 401,
                'message' => "You can't delete this item!"
            ] , 401);
        }

        $user->delete();
        return new ReturnResponseResource([
            'code' => 200 ,
            'message' => "Record has been deleted successfully!"
        ]);
    }
}
