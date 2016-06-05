<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\Story
 *
 * @property integer                  $id
 * @property integer                  $project_id
 * @property string                   $title
 * @property Carbon                   $created_at
 * @property Carbon                   $updated_at
 * @property-read \App\Models\Project $project
 * @method static Builder|Story whereId($value)
 * @method static Builder|Story whereProjectId($value)
 * @method static Builder|Story whereTitle($value)
 * @method static Builder|Story whereCreatedAt($value)
 * @method static Builder|Story whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Story extends Model
{
    protected $fillable = ['title'];

    protected $visible = ['id', 'project_id', 'title'];

    protected $casts = ['project_id' => 'int'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
