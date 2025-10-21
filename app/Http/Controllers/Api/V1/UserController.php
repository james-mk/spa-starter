<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Notifications\UserCredentialsNotification;

class UserController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $users = User::with('roles.permissions')->get();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'user_type' => 'nullable|string|max:50',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Store plain password before hashing for email
        $plainPassword = $data['password'];

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $uploadPath = public_path('uploads/profiles');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $image->move($uploadPath, $imageName);
            $data['profile_image'] = 'uploads/profiles/' . $imageName;
        }

        $data['password'] = Hash::make($data['password']);

        //if $data['user_type'] is not there, save the name of the role with id = $data['roles'][0]
        if (!isset($data['user_type']) && !empty($data['roles'])) {
            $role = \App\Models\Role::find($data['roles'][0]);
            if ($role) {
                $data['user_type'] = $role->name;
            } else {
                $data['user_type'] = 'N/A'; // Default user type if role not found
            }
        }
        $user = User::create($data);

        if (!empty($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        // Send credentials email to the new user
        try {
            $user->notify(new UserCredentialsNotification($user, $plainPassword));
        } catch (\Exception $e) {
            Log::error('Failed to send user credentials email: ' . $e->getMessage());
            // Continue execution even if email fails
        }

        // Log activity
        $roleNames = $user->roles->pluck('name')->toArray();
        $this->logActivity(
            'created',
            User::class,
            $user->id,
            "Created user: {$user->full_name} and sent credentials email",
            null,
            [
                'name' => $user->full_name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'roles' => $roleNames
            ]
        );

        return response()->json($user->load('roles.permissions'), 201);
    }

    public function show($id)
    {
        $user = User::with('roles.permissions')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Store old values
        $oldRoles = $user->roles->pluck('name')->toArray();
        $oldData = [
            'name' => $user->full_name,
            'email' => $user->email,
            'user_type' => $user->user_type,
            'roles' => $oldRoles
        ];

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'user_type' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
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

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        // Log activity
        $newRoles = $user->roles->pluck('name')->toArray();
        $this->logActivity(
            'updated',
            User::class,
            $user->id,
            "Updated user: {$user->full_name}",
            $oldData,
            [
                'name' => $user->full_name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'roles' => $newRoles
            ]
        );

        return response()->json($user->load('roles.permissions'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->full_name;
        $userEmail = $user->email;
        $roles = $user->roles->pluck('name')->toArray();

        if ($user->profile_image && file_exists(public_path($user->profile_image))) {
            unlink(public_path($user->profile_image));
        }

        $user->delete();

        // Log activity
        $this->logActivity(
            'deleted',
            User::class,
            $id,
            "Deleted user: {$userName}",
            [
                'name' => $userName,
                'email' => $userEmail,
                'roles' => $roles
            ],
            null
        );

        return response()->json(['message' => 'User deleted successfully']);
    }
}