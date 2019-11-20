<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function projectCanHaveTasks()
    {
        $project = (new ProjectFactory())->withTasks(1)->create();

        $this->actingAs($project->user)->post($project->path() . '/tasks', ['body' => 'Test task']);

        $this->get($project->path())->assertSee('Test task');
    }

    /** @test */
    public function onlyOwnerOfProjectCanAddTasks()
    {
        $this->signIn();

        $project = factory(Project::class)->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('project_tasks', ['body' => 'Test task']);
    }

    /** @test */
    public function onlyOwnerOfProjectCanUpdateTasks()
    {
        $this->signIn();

        $project = (new ProjectFactory())->withTasks(1)->create();

        $this->patch($project->tasks->first()->path(), ['body' => 'changed']);

        $this->assertDatabaseMissing('project_tasks', ['body' => 'changed']);
    }

    /** @test */
    public function taskCanBeUpdated()
    {
        $project = (new ProjectFactory())->withTasks(1)->create();

        $this->actingAs($project->user)->patch($project->tasks[0]->path(), [
                'body' => 'changed'
            ]);

        $this->assertDatabaseHas('project_tasks', [
            'body' => 'changed'
        ]);
    }

    /** @test */
    public function taskCanBeCompleted()
    {
        $project = (new ProjectFactory())->withTasks(1)->create();

        $this->actingAs($project->user)->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('project_tasks', [
            'body' => 'changed',
            'completed' => true
        ]);
    }

    /** @test */
    public function taskCanBeMarkedAsIncomplete()
    {
        $project = (new ProjectFactory())->withTasks(1)->create();

        $this->actingAs($project->user)->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('project_tasks', [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->actingAs($project->user)->patch($project->tasks[0]->path(), [
            'body' => 'changed again',
            'completed' => false
        ]);

        $this->assertDatabaseHas('project_tasks', [
            'body' => 'changed again',
            'completed' => false
        ]);
    }

    /** @test */
    public function taskCanBeDeleted()
    {
        $project = (new ProjectFactory())->withTasks(1)->create();

        $this->actingAs($project->user)->delete($project->tasks->first()->path());

        $this->assertDatabaseMissing('project_tasks', ['body' => $project->tasks->first()->body]);
    }

    /** @test */
    public function taskRequiresBody()
    {
        $project = (new ProjectFactory())->create();

        $attributes = factory('App\Task')->raw(['body' => '']);

        $this->actingAs($project->user)
            ->post($project->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }
}
