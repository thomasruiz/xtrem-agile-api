<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\Project
 *
 * @property integer                 $id
 * @property string                  $name
 * @property Carbon                  $created_at
 * @property Carbon                  $updated_at
 * @property-read Collection|Story[] $stories
 * @method static Builder|Project whereId($value)
 * @method static Builder|Project whereName($value)
 * @method static Builder|Project whereCreatedAt($value)
 * @method static Builder|Project whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Project extends Model
{
    protected $fillable = ['name'];

    protected $visible = ['id', 'name'];

    public function stories()
    {
        return $this->hasMany(Story::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
