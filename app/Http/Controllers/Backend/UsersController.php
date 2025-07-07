<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;



class UsersController extends Controller
{
    public function index()
    {
        $role = session()->get('role');
        $id = session()->get('id');
        if ($role != 'admin') {
            $data = DB::table('users')
                ->select('id', 'name', 'email', 'username', 'role')
                ->where('id', $id)
                ->get();
        } else {
            $data = DB::table('users')
                ->select('id', 'name', 'email', 'username', 'role')
                ->get();
        }

        return view('backend.users.index', compact('data'));
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|string|max:50',
            ]);

            // Create user
            DB::table('users')->insert([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function edit($id)
    {
        // fetch user data by id
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
    }
    public function update($id, Request $request)
    {
        // fetch user data by id
        $user = DB::table('users')->where('id', $id)->first();
        // edit user data if they dont change password , the password will not be updated using try catch like create function create
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'string|max:255|unique:users,username,' . $id,
                'role' => 'required|string|max:50',
            ]);

            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            DB::table('users')->where('id', $id)->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        try {
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }

            // Delete user
            DB::table('users')->where('id', $id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
