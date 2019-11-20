<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itHasPath()
    {
        $project = factory('App\Project')->create();

        $this->assertEquals('/projects/' . $project->id, $project->path());
    }

    /** @test */
    public function belongsToUser()
    {
        $project = factory('App\Project')->create();

        $this->assertInstanceOf(User::class, $project->user);
    }

    /** @test */
    public function canAddTask()
    {
        $project = factory('App\Project')->create();

        $task = $project->addTask('Test task');

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }
}
