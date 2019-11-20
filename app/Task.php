<?php

namespace App;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use RecordsActivity;

    protected $table = 'project_tasks';

    protected $fillable = ['project_id', 'body', 'completed'];

    protected $touches = ['project'];

    protected $casts = [
        'completed' => 'boolean'
    ];

    public static $recordableEvents = ['created', 'deleted'];

    public function path()
    {
        return $this->project->path() . '/tasks/' . $this->id;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function complete()
    {
        $this->update(['completed' => true]);

        $this->recordActivity('task_completed');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);

        $this->recordActivity('task_incompleted');
    }
}
