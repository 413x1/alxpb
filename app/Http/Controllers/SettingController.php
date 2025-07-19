<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('pages.admin.setting.index', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.auth()->id(),
            'email' => 'required|email|max:255|unique:users,email,'.auth()->id(),
            'password' => 'nullable|string|min:8',
        ], [
            'password.min' => 'The password must be at least 8 characters.',
            'username.unique' => 'The username has already been taken.',
            'email.unique' => 'The email address has already been taken.',
        ]);

        $user = auth()->user();

        $user->name = $validate['name'];
        $user->username = $validate['username'];
        $user->email = $validate['email'];
        if (! empty($validate['password'])) {
            $user->password = bcrypt($validate['password']);
        }

        $user->save();

        return redirect()->route('dashboard.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
