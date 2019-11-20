<?php

namespace Tests\Unit;

use App\Project;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function taskBelongsToProject()
    {
        $task = factory(Task::class)->create();

        $this->assertInstanceOf(Project::class, $task->project);
    }

    /** @test */
    public function itHasPath()
    {
        $task = factory(Task::class)->create();

        $this->assertEquals($task->project->path() . '/tasks/' . $task->id, $task->path());
    }

    /** @test */
    public function updatingTaskUpdatesProject()
    {
        $project = factory(Project::class)->create(['updated_at' => '2019-10-10 22:22:22']);
        $task = $project->addTask('Test task');

        $this->assertEquals(Project::find($project->id)->updated_at, $task->updated_at);
    }

    /** @test */
    public function canBeCompleted()
    {
        $task = factory(Task::class)->create();

        $this->assertFalse($task->completed);

        $task->complete();

        $this->assertTrue($task->fresh()->completed);
    }

    /** @test */
    public function taskCanBeIncompleted()
    {
        $task = factory(Task::class)->create(['completed' => true]);

        $this->assertTrue($task->completed);

        $task->incomplete();

        $this->assertFalse($task->fresh()->completed);
    }
}
