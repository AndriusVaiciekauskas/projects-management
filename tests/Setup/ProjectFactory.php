<?php


namespace Tests\Setup;


use App\Project;
use App\Task;
use App\User;

class ProjectFactory
{
    protected $task_count = 0;

    protected $user;

    public function ownedBy($user)
    {
        $this->user = $user;

        return $this;
    }

    public function withTasks($task_count)
    {
        $this->task_count = $task_count;

        return $this;
    }

    public function create()
    {
        $project = factory(Project::class)->create([
            'user_id' => $this->user ?? factory(User::class)
        ]);

        factory(Task::class, $this->task_count)->create([
            'project_id' => $project->id
        ]);

        return $project;
    }
}
