<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;

class UserController extends BaseController
{
    public function showAll(Request $request)
    {
        // return User::get();
        return $this->sendResponse(User::get(), 'Assets retrieved successfully.');
    }

    public function store(Request $request)
    {
        return User::create($request->all());
    }

    public function show($id)
    {

        $user = User::find($id);
        $currentUser = Auth::user();

        $userRole = $currentUser->role()->first();

        if($userRole->role != 'admin'){
            if($user->id != $currentUser->id){
                return $this->sendError('Unauthorized');
            }
        }

        if (is_null($user)) {
            return $this->sendError('User not found.');
        }

        $assets = $user->assets;
        $num_pending_assets = $assets->where('isVerified', 0)->count();
        $num_accepted_assets = $assets->where('isVerified', 1)->count();

        $userRole = $user->role()->get()->all();

        $investor = 0;
        $AM = 0;

        foreach($userRole as &$role){
            if($role->role == 'investor'){
                $investor = 1;
            }
            else if($role->role == 'AM'){
                $AM = 1;
            }
        }

        $user->investor = $investor;
        $user->AM = $AM;
        $user->num_pending_assets = $num_pending_assets;
        $user->num_accepted_assets = $num_accepted_assets;
        
        return $this->sendResponse($user, 'User retrieved successfully.');
    }

    public function update(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found.'
            ], 403);
        }

        $user->update(request()->except(['investor', 'AM']));

        if(request()->json('investor') == 1){
            $this->createRole('investor', $userId);
        }

        if(request()->json('AM') == 1){
            $this->createRole('AM', $userId);
        }

        // check if user is a basic user
        $query = Role::select('*')->where([
            ['role', '=', 'basic'],
            ['user_id', '=', $userId]
        ])->get();

        if(count($query) == 1){
            // delete that record if exist (user no longer a basic user)
            Role::select('*')->where([
                ['role', '=', 'basic'],
                ['user_id', '=', $userId]
            ])->delete();
        }

        return response()->json(['message' => 'User updated successfully.']);
    }

    public function sendDocument()
    {
        $user = Auth::user();
        $user->userState = 1;
        $user->save();

        return response()->json(['message'=>'User updated successfully.']);
    }

    public function delete(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found.'
            ], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }

    private function createRole($role, $userId)
    {
        $roles = array("AM", "investor");
        if (!in_array($role, $roles)){
            return $this->sendError('Invalid role', ['error' => "Invalid role"]);
        }

        $user = User::find($userId);

        if (is_null($user)) {
            return $this->sendError('User not found.');
        }

        $role = $role;
        $user_id = $userId;

        // check if record exist
        $query = Role::select('*')->where([
            ['role', '=', $role],
            ['user_id', '=', $user_id]
        ])->get();

        if(count($query) == 1){
            // delete that record if exist
            Role::select('*')->where([
                ['role', '=', $role],
                ['user_id', '=', $user_id]
            ])->delete();
        }
        
        // create new
        $role = Role::create([
            'role' => $role,
            'user_id' => $user->id,
        ]);

        return response()->json(['message' => 'Role added.']);
    }

}