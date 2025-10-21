<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Traits\LogsActivity;

class ProfileController extends Controller
{
    use LogsActivity;

    public function show(Request $request)
    {
        $user = $request->user()->load('roles.permissions');
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // Store old values
        $oldData = [
            'name' => $user->full_name,
            'email' => $user->email,
            'user_type' => $user->user_type
        ];

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'user_type' => 'nullable|string|max:50',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                unlink(public_path($user->profile_image));
            }

            $image = $request->file('profile_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $uploadPath = public_path('uploads/profiles');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $image->move($uploadPath, $imageName);
            $data['profile_image'] = 'uploads/profiles/' . $imageName;
        }

        $user->update($data);

        // Log activity
        $this->logActivity(
            'updated',
            get_class($user),
            $user->id,
            "Updated own profile",
            $oldData,
            [
                'name' => $user->full_name,
                'email' => $user->email,
                'user_type' => $user->user_type
            ]
        );

        return response()->json($user->load('roles.permissions'));
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect',
                'errors' => [
                    'current_password' => ['Current password is incorrect']
                ]
            ], 422);
        }

        $user->update([
            'password' => Hash::make($data['password'])
        ]);

        // Log activity
        $this->logActivity(
            'updated',
            get_class($user),
            $user->id,
            "Changed own password",
            null,
            null
        );

        return response()->json(['message' => 'Password updated successfully']);
    }
}