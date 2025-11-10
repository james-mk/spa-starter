<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Permission;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;


use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'user_type',
        'email',
        'password',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    protected $appends = ['full_name', 'profile_image_url'];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return url($this->profile_image);
        }
        return null;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token, $this->email));
    }


    // Check if user has a role
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    // Assign role
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }
        if ($role) {
            $this->roles()->syncWithoutDetaching([$role->id]);
        }
        return $this;
    }

    // Remove role
    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }
        if ($role) {
            $this->roles()->detach($role->id);
        }
        return $this;
    }

    // Check permission via roles
    public function hasPermission($permissionName)
    {
        return $this->roles()
            ->whereHas('permissions', function ($q) use ($permissionName) {
                $q->where('name', $permissionName);
            })->exists();
    }



    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    // public function permissions()
    // {
    //     // Get all permissions through roles
    //     return $this->roles->load('permissions')->pluck('permissions')->flatten()->unique('id');
    // }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    /**
     * Route notifications for mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }
}
