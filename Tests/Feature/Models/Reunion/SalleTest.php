<?php

namespace Tests\Feature\Models\Reunion;

use App\Models\Reunion\Salle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SalleTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @var string
     */
    private const MODEL = 'salle';

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
        $salle = Salle::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.show', ['modelVariable' => $salle->id]));

        $response->assertRedirect('login');
    }

    public function test_edit_need_login()
    {
        $salle = Salle::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.edit', ['modelVariable' => $salle->id]));

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
        $salle = Salle::factory()
            ->make();
        $data = array_merge($salle->toArray());

        $response = $this->post(route(self::MODEL . '.store'), $data);
        $response->assertUnauthorized();
    }

    public function test_show_need_admin()
    {
        $this->setUser();
        $salle = Salle::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.show', ['modelVariable' => $salle->id]));

        $response->assertUnauthorized();
    }

    public function test_edit_need_admin()
    {
        $this->setUser();
        $salle = Salle::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.edit', ['modelVariable' => $salle->id]));

        $response->assertUnauthorized();
    }

    public function test_update_need_admin()
    {
        $this->setUser();
        $salle = Salle::factory()
            ->create();
        $data = array_merge($salle->toArray());
        $data['id'] = $salle->id;

        $response = $this->put(route(self::MODEL . '.update', ['modelVariable' => $salle->id]), $data);

        $response->assertUnauthorized();
    }

    public function test_delete_need_admin()
    {
        $this->setUser();
        $salle = Salle::factory()
            ->create();

        $response = $this->delete(route(self::MODEL . '.destroy', ['modelVariable' => $salle->id]));

        $response->assertUnauthorized();
    }

    public function test_undelete_need_admin()
    {
        $this->setUser();
        $salle = Salle::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.undelete', ['modelVariable' => $salle->id]));

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

        $salle = Salle::factory()
            ->make();
        $data = array_merge($salle->toArray());

        $response = $this->post(route(self::MODEL . '.store'), $data);

        $response->assertSessionHas('ok');
    }

    public function test_edit()
    {
        $this->setUser('admin');

        $salle = Salle::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.edit', ['modelVariable' => $salle->id]));

        $response->assertStatus(200);
    }

    public function test_update()
    {
        $this->setUser('admin');

        $salle = Salle::factory()
            ->create();
        $data = array_merge($salle->toArray());

        $response = $this->put(route(self::MODEL . '.update', ['modelVariable' => $salle->id]), $data);
        $modelVariable = Salle::find($salle->id);

        $this->assertNotNull($salle->user_id_modification);
        $response->assertSessionHas('ok');
    }

    public function test_delete()
    {
        $this->setUser('admin');

        $salle = Salle::factory()
            ->create();

        $response = $this->delete(route(self::MODEL . '.destroy', ['modelVariable' => $salle->id]));

        $this->assertSoftDeleted(Salle::class);
        $response->assertSessionHas(['ok']);
    }

    public function test_undelete()
    {
        $this->setUser('admin');

        $salle = Salle::factory()
            ->create();

        $response = $this->delete(route(self::MODEL . '.destroy', ['modelVariable' => $salle->id]));
        $this->assertSoftDeleted(Salle::class);
        $response->assertSessionHas(['ok']);

        $response = $this->get(route(self::MODEL . '.undelete', ['modelVariable' => $salle->id]));

        $this->assertNull($salle->user_id_suppression);
        $response->assertSessionHas(['ok']);
    }

    public function test_json()
    {
        $this->setUser('admin');

        $response = $this->get(route(self::MODEL . '.json'));

        $response->assertJsonStructure();
    }
}
