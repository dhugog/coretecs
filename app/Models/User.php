<?php

namespace CoreTecs\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;    
    use Notifiable;

    /**
     * Authorizes the user by their permission level
     * 
     * If their permission level is less then the specified, returns false, else, returns true
     * 
     * @param int $level
     * @return boolean
     */
    function authorized($level) {
        if($this->permission < $level) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'id_role', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function role()
    {
        return $this->hasOne(\CoreTecs\Models\Role::class, 'id', 'id_role');
        //                                         Foreign Key , Local Key
    }

    /**
     * Checks if User has access to $permissions.
     */
    public function hasAccess(array $permissions) : bool
    {        
        // check if the permission is available in any role
        // foreach ($this->roles as $role) {
            if($this->role->hasAccess($permissions)) {
                return true;
            }
        // }
        return false;
    }

    public function hasRole($name)
    {
        // foreach($this->roles as $role)
        // {
            if($this->role->name == $name) return true;
        // }

        return false;
    }

    public function assignRole($role)
    {
        return $this->role()->attach($role);
    }

    public function removeRole($role)
    {
        return $this->role()->detach($role);
    }     

    // public function hasActived()
    // {
    //     return is_null($this->parceiro) ? true : (bool) $this->parceiro->isativo;
    // }
}
