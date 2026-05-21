<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserProfileController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::guest()) {
            return redirect('/login');
        }

        return view('profile', [
            'user' => Auth::user(),
        ]);
    }
    public function update(Request $request)
    {
        if (Auth::guest()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(500, 'User instance not found');
        }

        // Validate request
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:300', 'unique:users,username,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Now properly handles image uploads
            'bio' => ['nullable', 'string', 'max:500'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        // Handle avatar upload if a new file is provided
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            // Store new avatar and get the path
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        } else {
            $avatarPath = $user->avatar; // Keep the existing avatar
        }

        // Update user attributes
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'avatar' => $avatarPath, // Save the uploaded file path
            'bio' => $request->bio,
            'email' => $request->email,
        ]);

        return redirect('/profile')->with('success', 'Profile updated successfully!');
    }

    public function destroy(Request $request)
    {
        if (Auth::guest()) {
            return redirect('/login');
        }
        if (Auth::user()->id !== $request->user()->id) {
            return redirect('/')->with('error', 'You are not authorized to delete this user.');
        }

        // Validate request
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        // Delete user
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(500, 'User instance not found');
        }

        $user->delete();
        return redirect('/')->with('success', 'User deleted successfully!');
    }
}
