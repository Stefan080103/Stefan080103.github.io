<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username,' . $request->user()->id],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        try {
            $user = $request->user();
            
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Ensure avatars directory exists
                $avatarPath = storage_path('app/public/avatars');
                if (!File::exists($avatarPath)) {
                    File::makeDirectory($avatarPath, 0755, true);
                }
                
                // Delete old avatar if exists
                if ($user->avatar) {
                    $oldAvatarPath = storage_path('app/public/' . $user->avatar);
                    if (File::exists($oldAvatarPath)) {
                        File::delete($oldAvatarPath);
                    }
                }
                
                // Generate unique filename
                $avatar = $request->file('avatar');
                $filename = 'avatar_' . $user->id . '_' . time() . '.' . $avatar->getClientOriginalExtension();
                $fullPath = $avatarPath . '/' . $filename;
                
                // Move the uploaded file
                if ($avatar->move($avatarPath, $filename)) {
                    $user->avatar = 'avatars/' . $filename;
                }
            }

            $user->fill($request->only(['name', 'username', 'email', 'bio']));
            $user->save();

            return redirect()->route('profile.edit')->with('status', 'Profilul a fost actualizat cu succes!');
            
        } catch (\Exception $e) {
            \Log::error('Error updating profile: ' . $e->getMessage());
            return back()->with('error', 'A apărut o eroare la actualizarea profilului: ' . $e->getMessage());
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Parola nu este corectă.']);
        }

        // Delete user's avatar if exists
        if ($user->avatar) {
            $avatarPath = storage_path('app/public/' . $user->avatar);
            if (File::exists($avatarPath)) {
                File::delete($avatarPath);
            }
        }

        // Delete user's posts images
        foreach ($user->posts as $post) {
            $postImagePath = storage_path('app/public/' . $post->image_path);
            if (File::exists($postImagePath)) {
                File::delete($postImagePath);
            }
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
