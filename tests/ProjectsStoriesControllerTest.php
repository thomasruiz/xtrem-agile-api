<?php

namespace Tests;

use App\Models\Project;
use App\Models\Story;
use App\Models\User;

class ProjectsStoriesControllerTest extends TestCase
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

    public function testGetAllTheStories()
    {
        $stories = $this->project->stories()->saveMany(factory(Story::class, 2)->make());

        $this->jsonJWT('get', '/projects/1/stories');

        $this->assertResponseOk();
        $this->see([
            'stories' => [
                [
                    'id'         => 1,
                    'project_id' => 1,
                    'title'      => $stories[0]->title,
                ],
                [
                    'id'         => 2,
                    'project_id' => 1,
                    'title'      => $stories[1]->title,
                ],
            ],
        ]);
    }

    public function testGetAllTheStoriesWhenNotAuthenticated()
    {
        $this->json('get', '/projects/1/stories');

        $this->assertResponseStatus(400);
    }

    public function testGetTheStoriesFromAInaccessibleProject()
    {
        $this->authenticatedUser = null;

        $this->jsonJWT('get', '/projects/1/stories');

        $this->assertResponseStatus(403);
    }

    public function testCreateANewStory()
    {
        $this->jsonJWT('post', '/projects/1/stories', ['title' => $title = $this->faker->sentence]);

        $this->assertResponseStatus(201);
        $this->see(['story' => ['id' => 1, 'project_id' => 1, 'title' => $title]]);
        $this->seeInDatabase('stories', ['title' => $title, 'project_id' => 1]);
    }

    public function testCreateANewStoryWithNoTitle()
    {
        $this->jsonJWT('post', '/projects/1/stories', []);

        $this->assertResponseStatus(422);
        $this->seeJsonStructure(['title']);
        $this->dontSeeInDatabase('stories', []);
    }

    public function testCreateANewStoryWhenNotAuthenticated()
    {
        $this->json('post', '/projects/1/stories', []);

        $this->assertResponseStatus(400);
    }

    public function testCreateANewStoryInAnInaccesibleProject()
    {
        $this->authenticatedUser = null;

        $this->jsonJWT('post', '/projects/1/stories', []);

        $this->assertResponseStatus(403);
    }
}
