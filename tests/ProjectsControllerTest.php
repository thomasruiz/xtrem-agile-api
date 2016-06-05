<?php

namespace Tests;

use App\Models\Project;
use App\Models\User;

class ProjectsControllerTest extends TestCase
{
    public function testGetAProject()
    {
        $project = factory(Project::class)->create();
        $this->authenticatedUser = factory(User::class)->create();
        $project->users()->save($this->authenticatedUser);

        $this->jsonJWT('get', '/projects/1');

        $this->assertResponseOk();
        $this->see(['project' => ['id' => 1, 'name' => $project->name]]);
    }

    public function testGetAProjectWhenNotAuthenticated()
    {
        factory(Project::class)->create();

        $this->json('get', '/projects/1');

        $this->assertResponseStatus(400);
    }

    public function testGetAnInaccessibleProject()
    {
        factory(Project::class)->create();
        $this->authenticatedUser = null;

        $this->jsonJWT('get', '/projects/1');

        $this->assertResponseStatus(403);
    }

    public function testCreateAProject()
    {
        $this->jsonJWT('post', '/projects', ['name' => $name = $this->faker->words(3, true)]);

        $this->assertResponseStatus(201);
        $this->see(['project' => ['id' => 1, 'name' => $name]]);
        $this->seeInDatabase('projects', ['id' => 1, 'name' => $name]);
        $this->seeInDatabase('project_user', ['project_id' => 1, 'user_id' => 1]);
    }

    public function testCreateAProjectWithoutAName()
    {
        $this->jsonJWT('post', '/projects', []);

        $this->assertResponseStatus(422);
        $this->seeJsonStructure(['name']);
    }

    public function testCreateAProjectWhenNotAuthenticated()
    {
        $this->json('post', '/projects', []);

        $this->assertResponseStatus(400);
    }
}
