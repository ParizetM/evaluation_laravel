<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\module\modelName;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @var string
     */
    private const MODEL = 'modelVariable';

    public function test_index_need_login()
    {
        $response = $this->get(route(self::MODEL . '.index'));

        $response->assertRedirect('login');
    }

    public function test_create_need_login()
    {
        $response = $this->get(route(self::MODEL . '.create'));

        $response->assertRedirect('login');
    }

    public function test_show_need_login()
    {
        $modelVariable = modelName::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.show', ['modelVariable' => $modelVariable->id]));

        $response->assertRedirect('login');
    }

    public function test_edit_need_login()
    {
        $modelVariable = modelName::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.edit', ['modelVariable' => $modelVariable->id]));

        $response->assertRedirect('login');
    }

    public function test_index_need_admin()
    {
        $this->setUser();

        $response = $this->get(route(self::MODEL . '.index'));

        $response->assertUnauthorized();
    }

    public function test_create_need_admin()
    {
        $this->setUser();

        $response = $this->get(route(self::MODEL . '.create'));

        $response->assertUnauthorized();
    }

    public function test_store_need_admin()
    {
        $this->setUser();
        $modelVariable = modelName::factory()
            ->make();
        $data = array_merge($modelVariable->toArray());

        $response = $this->post(route(self::MODEL . '.store'), $data);
        $response->assertUnauthorized();
    }

    public function test_show_need_admin()
    {
        $this->setUser();
        $modelVariable = modelName::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.show', ['modelVariable' => $modelVariable->id]));

        $response->assertUnauthorized();
    }

    public function test_edit_need_admin()
    {
        $this->setUser();
        $modelVariable = modelName::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.edit', ['modelVariable' => $modelVariable->id]));

        $response->assertUnauthorized();
    }

    public function test_update_need_admin()
    {
        $this->setUser();
        $modelVariable = modelName::factory()
            ->create();
        $data = array_merge($modelVariable->toArray());
        $data['id'] = $modelVariable->id;

        $response = $this->put(route(self::MODEL . '.update', ['modelVariable' => $modelVariable->id]), $data);

        $response->assertUnauthorized();
    }

    public function test_delete_need_admin()
    {
        $this->setUser();
        $modelVariable = modelName::factory()
            ->create();

        $response = $this->delete(route(self::MODEL . '.destroy', ['modelVariable' => $modelVariable->id]));

        $response->assertUnauthorized();
    }

    public function test_undelete_need_admin()
    {
        $this->setUser();
        $modelVariable = modelName::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.undelete', ['modelVariable' => $modelVariable->id]));

        $response->assertUnauthorized();
    }

    public function test_json_need_admin()
    {
        $this->setUser();

        $response = $this->get(route(self::MODEL . '.json'));

        $response->assertUnauthorized();
    }

    public function test_index()
    {
        $this->setUser('admin');

        $response = $this->get(route(self::MODEL . '.index'));

        $response->assertStatus(200);
    }

    public function test_create()
    {
        $this->setUser('admin');

        $response = $this->get(route(self::MODEL . '.create'));

        $response->assertStatus(200);
    }

    public function test_store()
    {
        $this->setUser('admin');

        $modelVariable = modelName::factory()
            ->make();
        $data = array_merge($modelVariable->toArray());

        $response = $this->post(route(self::MODEL . '.store'), $data);

        $response->assertSessionHas('ok');
    }

    public function test_edit()
    {
        $this->setUser('admin');

        $modelVariable = modelName::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.edit', ['modelVariable' => $modelVariable->id]));

        $response->assertStatus(200);
    }

    public function test_update()
    {
        $this->setUser('admin');

        $modelVariable = modelName::factory()
            ->create();
        $data = array_merge($modelVariable->toArray());

        $response = $this->put(route(self::MODEL . '.update', ['modelVariable' => $modelVariable->id]), $data);
        $modelVariable = modelName::find($modelVariable->id);

        $this->assertNotNull($modelVariable->user_id_modification);
        $response->assertSessionHas('ok');
    }

    public function test_delete()
    {
        $this->setUser('admin');

        $modelVariable = modelName::factory()
            ->create();

        $response = $this->delete(route(self::MODEL . '.destroy', ['modelVariable' => $modelVariable->id]));

        $this->assertSoftDeleted(modelName::class);
        $response->assertSessionHas(['ok']);
    }

    public function test_undelete()
    {
        $this->setUser('admin');

        $modelVariable = modelName::factory()
            ->create();

        $response = $this->delete(route(self::MODEL . '.destroy', ['modelVariable' => $modelVariable->id]));
        $this->assertSoftDeleted(modelName::class);
        $response->assertSessionHas(['ok']);

        $response = $this->get(route(self::MODEL . '.undelete', ['modelVariable' => $modelVariable->id]));

        $this->assertNull($modelVariable->user_id_suppression);
        $response->assertSessionHas(['ok']);
    }

    public function test_json()
    {
        $this->setUser('admin');

        $response = $this->get(route(self::MODEL . '.json'));

        $response->assertJsonStructure();
    }
}
