<?php

namespace Tests;

use App\Models\Project;
use App\Models\Story;
use App\Models\User;

class StoriesControllerTest extends TestCase
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

    public function testRetrieveAStory()
    {
        $story = $this->project->stories()->save(factory(Story::class)->make());

        $this->jsonJWT('get', '/stories/1');

        $this->assertResponseOk();
        $this->see(['story' => ['id' => 1, 'project_id' => 1, 'title' => $story->title]]);
    }

    public function testRetrieveAStoryWhenNotAuthenticated()
    {
        $this->project->stories()->save(factory(Story::class)->make());

        $this->json('get', '/stories/1');

        $this->assertResponseStatus(400);
    }

    public function testRetrieveAStoryInAnInaccesibleProject()
    {
        $this->authenticatedUser = null;
        $this->project->stories()->save(factory(Story::class)->make());

        $this->jsonJWT('get', '/stories/1');

        $this->assertResponseStatus(403);
    }

    public function testUpdateAStory()
    {
        $this->project->stories()->save(factory(Story::class)->make());

        $this->jsonJWT('put', '/stories/1', ['title' => $title = $this->faker->sentence]);

        $this->assertResponseOk();
        $this->see(['story' => ['id' => 1, 'project_id' => 1, 'title' => $title]]);
        $this->seeInDatabase('stories', ['id' => 1, 'title' => $title]);
    }

    public function testUpdateAStoryWithNoTitle()
    {
        $oldStory = $this->project->stories()->save(factory(Story::class)->make());

        $this->jsonJWT('put', '/stories/1', []);

        $this->assertResponseStatus(422);
        $this->see(['title' => ['A story needs a title.']]);
        $this->seeInDatabase('stories', ['id' => 1, 'title' => $oldStory->title]);
    }

    public function testUpdateAStoryWhenNotAuthenticated()
    {
        $this->project->stories()->save(factory(Story::class)->make());

        $this->json('put', '/stories/1', ['title' => $this->faker->sentence]);

        $this->assertResponseStatus(400);
    }

    public function testUpdateAStoryInAnInaccesibleProject()
    {
        $this->authenticatedUser = null;
        $this->project->stories()->save(factory(Story::class)->make());

        $this->jsonJWT('put', '/stories/1', ['title' => $this->faker->sentence]);

        $this->assertResponseStatus(403);
    }

    public function testDeleteAStory()
    {
        $this->project->stories()->save(factory(Story::class)->make());

        $this->jsonJWT('delete', '/stories/1');

        $this->assertResponseStatus(204);
        $this->dontSeeInDatabase('stories', []);
    }

    public function testDeleteAStoryWhenNotAuthenticated()
    {
        $this->project->stories()->save(factory(Story::class)->make());

        $this->json('delete', '/stories/1');

        $this->assertResponseStatus(400);
    }

    public function testDeleteAStoryInAnInaccesibleProject()
    {
        $this->authenticatedUser = null;
        $this->project->stories()->save(factory(Story::class)->make());

        $this->jsonJWT('delete', '/stories/1');

        $this->assertResponseStatus(403);
    }
}
