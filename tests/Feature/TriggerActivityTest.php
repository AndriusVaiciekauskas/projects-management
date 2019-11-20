<?php

namespace Tests\Feature;

use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creatingProjectRecordsActivity()
    {
        $project = (new ProjectFactory)->create();

        $this->assertCount(1, $project->activity);

        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('project_created', $activity->description);
            $this->assertNull($activity->changes);
        });
    }

    /** @test */
    public function updatingProjectRecordsActivity()
    {
        $this->withoutExceptionHandling();

        $project = (new ProjectFactory())->create();
        $original_title = $project->title;

        $project->update(['title' => 'Changed']);

        $this->assertCount(2, $project->activity);
        tap($project->activity->last(), function ($activity) use ($original_title, $project) {
            $this->assertEquals('project_updated', $activity->description);

            $expected = [
                'before' => ['title' => $original_title],
                'after' => ['title' => 'Changed']
            ];

            $this->assertEquals($expected, $activity->changes);
        });
    }

    /** @test */
    public function creatingTaskRecordsProjectActivity()
    {

        $this->withoutExceptionHandling();
        $project = (new ProjectFactory())->create();

        $project->addTask('Some task');

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('task_created', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('Some task', $activity->subject->body);
        });
    }

    /** @test */
    public function completingTaskRecordsProjectActivity()
    {
        $project = (new ProjectFactory())->withTasks(1)->create();
        $this->actingAs($project->user)->patch($project->tasks->first()->path(), [
            'body' => 'test task',
            'completed' => true
        ]);

        $this->assertCount(3, $project->activity);
        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('task_completed', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('test task', $activity->subject->body);
        });
    }

    /** @test */
    public function incompletingTaskRecordsProjectActivity()
    {
        $project = (new ProjectFactory())->withTasks(1)->create();
        $this->actingAs($project->user)->patch($project->tasks->first()->path(), [
            'body' => 'test task',
            'completed' => true
        ]);

        $this->assertCount(3, $project->activity);
        $this->assertDatabaseHas('activities', [
            'project_id' => $project->id,
            'description' => 'task_completed'
        ]);

        $this->actingAs($project->user)->patch($project->tasks->first()->path(), [
            'body' => 'test task 1',
            'completed' => false
        ]);

        $project->refresh();
        $this->assertCount(4, $project->activity);
        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('task_incompleted', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('test task 1', $activity->subject->body);
        });
    }

    /** @test */
    public function deleteingTaskCreatesActivity()
    {
        $this->withoutExceptionHandling();

        $project = (new ProjectFactory())->withTasks(1)->create();
        $this->actingAs($project->user)->delete($project->tasks->first()->path());

        $this->assertCount(3, $project->activity);
        $this->assertEquals('task_deleted', $project->activity->last()->description);
    }
}
