<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guestCantCreateProjects()
    {
        $project = factory('App\Project')->create();

        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->get($project->path() . '/edit')->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    /** @test */
    public function userCanCreateProject()
    {
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->words(2, true),
            'description' => $this->faker->words(4, true),
            'notes' => 'This is general notes'
        ];

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description)
            ->assertSee($project->notes);
    }

    /** @test */
    public function userCanUpdateProject()
    {
        $project = (new ProjectFactory())->create();

        $attributes = [
            'title' => 'Edited title',
            'description' => 'Edited description',
            'notes' => 'This is edited notes'
        ];

        $this->actingAs($project->user)
            ->patch($project->path(), $attributes)
            ->assertRedirect($project->path());

        $this->get($project->path() . '/edit')->assertOk();

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function userCanUpdateProjectNotes()
    {
        $project = (new ProjectFactory())->create();

        $attributes = ['notes' => 'This is edited notes'];

        $this->actingAs($project->user)
            ->patch($project->path(), $attributes)
            ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function userCanSeeTheirProject()
    {
        $project = (new ProjectFactory())->create();

        $this->actingAs($project->user)
            ->get($project->path())
            ->assertSee($project->title)
            ->assertSee(str_limit($project->description, 100));
    }

    /** @test */
    public function userCantSeeProjectsOfOthers()
    {
        $this->signIn();

        $project = (new ProjectFactory())->create();

        $this->get($project->path())
            ->assertStatus(403);
    }

    /** @test */
    public function userCantUpdateProjectsOfOthers()
    {
        $this->signIn();

        $project = (new ProjectFactory())->create();

        $this->patch($project->path(), ['notes' => 'changed'])
            ->assertStatus(403);
    }

    /** @test */
    public function onlyAuthorizedUserCanDeleteProject()
    {
        $project = (new ProjectFactory())->create();

        $this->delete($project->path())->assertRedirect('login');

        $this->actingAs($this->signIn())
            ->delete($project->path())
            ->assertStatus(403);

        $this->assertDatabaseHas('projects', $project->only('id'));
    }

    /** @test */
    public function userCanDeleteProject()
    {
        $this->withoutExceptionHandling();

        $project = (new ProjectFactory())->create();

        $this->actingAs($project->user)
            ->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function projectRequiresTitle()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function projectRequiresDescription()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
