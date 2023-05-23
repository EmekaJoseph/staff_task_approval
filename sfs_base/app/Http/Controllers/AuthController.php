<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

use App\Models\UserModel;

class AuthController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function userCreate(Request $req)
    {
        if (UserModel::where('email', $req->input('email'))->exists()) {
            return response()->json('exists', 203);
        }

        $account = new UserModel();
        $account->email = $req->input('email');
        $account->role = $req->input('role');
        $account->dept_id = $req->input('dept_id');
        $account->password = Hash::make($req->input('password'));
        $account->save();
        return response()->json('created', 200);
    }

    public function userLogin(Request $req)
    {
        $email = $req->input('email');
        $password = $req->input('password');

        // validate passwords?
        $account = UserModel::where('email', $email)->first();
        if (!$account || !Hash::check($password, $account->password)) {
            return response()->json('invalid login', 203);
        }

        // return logged-in credentials with token
        $dept = UserModel::find($account->user_id)->relatnDept;
        $data = [
            'id' => $account->user_id,
            'role' => $account->role,
            'dept' => $dept->dept_name,
            'email' => $account->email,
            'token' => $account->createToken('sfs_token')->plainTextToken,
        ];

        return response()->json($data, 200);
    }

    public function userLogout(Request $req)
    {
        try {
            $req->user()->currentAccessToken()->delete();
        } catch (\Throwable $th) {
        }
    }
}
