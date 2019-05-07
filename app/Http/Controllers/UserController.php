<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $users = $this->user->latest()->paginate(15);

        return view('users.index')->with([
            'users' => $users
        ]);
    }

    public function create($id = false, Role $role)
    {
        $user = (!$id) ? $this->user : $this->user->find($id);;
        $roles = $role->all();

        return view('users.create')->with([
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function createSave(Request $request)
    {
        $data = $request->all();

        $validator = \Validator::make($data, [
            'name' => 'required|min:3|max:150',
            'email' => 'required|email|min:3|max:100|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|numeric',
        ]);

        if ($validator->fails())
            return \redirect(route('users-create'))->withErrors($validator);

        $data['password'] = Hash::make($data['password']);

        $user = $this->user->create($data);
        $user->roles()->attach($data['role']);

        if (!$user) {
            abort(500);
        }

        return redirect(route('users-list'))->with(['success' => 'User has been save']);
    }

    public function updateSave($id = false, Request $request)
    {
        $data = $request->all();

        $validator = \Validator::make($data, [
            'name' => 'required|min:3|max:150',
            'email' => 'required|email|min:3|max:100|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required|numeric',
        ]);

        if ($validator->fails())
            return \redirect(route('users-create', ['id' => $id]))->withErrors($validator);

        if (empty($data['password']))
            unset($data['password']);
        else
            $data['password'] = Hash::make($data['password']);

        $user = $this->user->find($id);

        $user->roles()->detach();
        $user->roles()->attach($data['role']);

        $status = $user->update($data);

        if (!$status) {
            abort(500);
        }

        return redirect(route('users-list'))->with(['success' => 'User has been save']);
    }


    public function destroy($id)
    {
        $status = $this->user->destroy($id);

        if (!$status) {
            abort(500);
        }

        return redirect(route('users-list'))->with(['success' => 'User has been delete']);
    }
}
