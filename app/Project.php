<?php

namespace App;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use RecordsActivity;

    protected $fillable = ['title', 'description', 'notes'];

    public function path()
    {
        return "/projects/$this->id";
    }

    public function addTask($body)
    {
        return $this->tasks()->create(['body' => $body]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }
}
