<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $roles = array("admin", "AM", "investor", "basic");
        if (!in_array($request->role, $roles)){
            return $this->sendError('Invalid role', ['error' => "Invalid role"]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        try{

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendError('Unable to register', ['error' => "Email already in use"]);
        }

        $role = Role::create([
            'role' => 'basic',
            'user_id' => $user->id,
        ]);

        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User */

            $user = Auth::user();
            $userRole = $user->role()->first();
            
            $roleString = "";

            if ($userRole->role == "admin") {
                return $this->sendError('Admin cannot login here.', ['error' => 'Wrong entry point']);
            } 
            
            $userRole = $user->role()->get()->all();
            
            $roleString = "";

            if ($userRole) {
                $roleString = "";
                
                for ($i=0; $i < count($userRole); $i++){
                    $roleString .= $userRole[$i]->role . " "  ;
                }
                $this->scope = $roleString;
            }

            $token = $user->createToken($user->email . '-' . now(), [$this->scope]);

            $user = Auth::user();
            $success['token'] =  $token->accessToken;
            $success['name'] =  $user->name;
            $success['id'] = $user->id;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    public function adminLogin(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User */

            $user = Auth::user();
            $userRole = $user->role()->first();

            if ($userRole->role == "admin") {
                $this->scope = $userRole->role;
            } else {
                return $this->sendError('Unauthorised, only admins can login through this entry point', ['error' => 'Wrong entry point']);
            }

            $token = $user->createToken($user->email . '-' . now(), [$this->scope]);

            $user = Auth::user();
            $success['token'] =  $token->accessToken;
            $success['name'] =  $user->name;
            $success['id'] = $user->id;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }
}