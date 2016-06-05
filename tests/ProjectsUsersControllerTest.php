<?php

namespace Tests;

use App\Models\Project;
use App\Models\User;

class ProjectsUsersControllerTest extends TestCase
{
    /** @var Project */
    protected $project;

    public function setUp()
    {
        parent::setUp();

        $this->project = factory(Project::class)->create();
        $this->authenticatedUser = factory(User::class)->create();
        $this->project->users()->save($this->authenticatedUser);
    }

    public function testAddAUserToAProject()
    {
        $user = factory(User::class)->create();

        $this->jsonJWT('put', '/projects/1/users/' . $user->id);

        $this->assertResponseStatus(204);
        $this->seeInDatabase('project_user', ['project_id' => 1, 'user_id' => $user->id]);
    }

    public function testAddAUserToAProjectThatAreAlreadyRelated()
    {
        $user = factory(User::class)->create();
        $this->project->users()->save($user);

        $this->jsonJWT('put', '/projects/1/users/' . $user->id);

        $this->assertResponseStatus(400);
        $this->see(['user' => ['This user already has access to this project.']]);
    }

    public function testAddAUserToAProjectWhenNotAuthenticated()
    {
        $user = factory(User::class)->create();

        $this->json('put', '/projects/1/users/' . $user->id);

        $this->assertResponseStatus(400);
    }

    public function testAddAUserToAnInaccessibleProject()
    {
        $this->authenticatedUser = null;
        $user = factory(User::class)->create();

        $this->jsonJWT('put', '/projects/1/users/' . $user->id);

        $this->assertResponseStatus(403);
    }

    public function testRemoveAUserFromAProject()
    {
        $user = factory(User::class)->create();
        $this->project->users()->save($user);

        $this->jsonJWT('delete', '/projects/1/users/' . $user->id);

        $this->assertResponseStatus(204);
        $this->notSeeInDatabase('project_user', ['project_id' => 1, 'user_id' => $user->id]);
    }

    public function testRemoveAUserFromAnUnexistingProject()
    {
        $this->jsonJWT('delete', '/projects/2/users/1');

        $this->assertResponseStatus(404);
    }

    public function testRemoveAnUnexistingUserFromAProject()
    {
        $this->jsonJWT('delete', '/projects/1/users/5');

        $this->assertResponseStatus(404);
    }

    public function testRemoveAUserFromAProjectWhenTheyAreNotRelated()
    {
        $this->jsonJWT('delete', '/projects/1/users/1');

        $this->assertResponseStatus(204);
    }

    public function testRemoveAUserFromAProjectWhenNotAuthenticated()
    {
        $user = factory(User::class)->create();

        $this->json('delete', '/projects/1/users/' . $user->id);

        $this->assertResponseStatus(400);
    }

    public function testRemoveAUserFromAnInaccessibleProject()
    {
        $this->authenticatedUser = null;
        $user = factory(User::class)->create();

        $this->jsonJWT('delete', '/projects/1/users/' . $user->id);

        $this->assertResponseStatus(403);
    }
}
