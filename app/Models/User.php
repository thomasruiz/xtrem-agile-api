<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\User
 *
 * @property integer $id
 * @property string  $email
 * @property string  $name
 * @property string  $password
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $fillable = ['email', 'name'];

    protected $visible = ['id', 'email', 'name'];

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
