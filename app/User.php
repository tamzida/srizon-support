<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Venturecraft\Revisionable\RevisionableTrait;


/**
 * App\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $alt_email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ticket[] $tickets
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereAltEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereDeletedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use RevisionableTrait;

    protected $visible = ['id', 'name', 'email', 'roles'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot() {
        parent::boot();
        if(! \Schema::hasTable('tickets')) return;

        static::deleting(function(User $user) {
            foreach($user->tickets as $ticket){
                $ticket->delete();
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }

    /**
     * @return Permission[]|\Illuminate\Database\Eloquent\Collection|null|static
     */
    public function permissions()
    {
        $user_permissions = collect([]);
        foreach ($this->roles as $role) {
            $user_permissions = $role->permissions->merge($user_permissions);
        }
        return $user_permissions;
    }

    /**
     * @param String $role_alias
     * @return \App\User
     */
    public function assignRole($role_alias)
    {
        if (!$this->roles->contains(Role::whereAlias($role_alias)->first()->id)) {
            $this->roles()->attach(Role::whereAlias($role_alias)->firstOrFail());
        }
        return $this;
    }

    /**
     * @param String[] $role_aliases
     * @return \App\User
     */
    public function assignRoles($role_aliases)
    {
        foreach ($role_aliases as $role_alias) {
            $this->assignRole($role_alias);
        }
        return $this;
    }

    /**
     * @param String $role_alias
     * @return \App\User
     */
    public function revokeRole($role_alias)
    {
        $this->roles()->detach(Role::whereAlias($role_alias)->firstOrFail());
        return $this;
    }

    /**
     * @param String[] $role_aliases
     * @return \App\User
     */
    public function revokeRoles($role_aliases)
    {
        foreach ($role_aliases as $role_alias) {
            $this->revokeRole($role_alias);
        }
        return $this;
    }
}
