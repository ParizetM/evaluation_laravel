<?php

namespace Tests\Feature\Models\Reunion;

use App\Models\Reunion\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * @var string
     */
    private const MODEL = 'reservation';

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
        $reservation = Reservation::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.show', ['modelVariable' => $reservation->id]));

        $response->assertRedirect('login');
    }

    public function test_edit_need_login()
    {
        $reservation = Reservation::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.edit', ['modelVariable' => $reservation->id]));

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
        $reservation = Reservation::factory()
            ->make();
        $data = array_merge($reservation->toArray());

        $response = $this->post(route(self::MODEL . '.store'), $data);
        $response->assertUnauthorized();
    }

    public function test_show_need_admin()
    {
        $this->setUser();
        $reservation = Reservation::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.show', ['modelVariable' => $reservation->id]));

        $response->assertUnauthorized();
    }

    public function test_edit_need_admin()
    {
        $this->setUser();
        $reservation = Reservation::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.edit', ['modelVariable' => $reservation->id]));

        $response->assertUnauthorized();
    }

    public function test_update_need_admin()
    {
        $this->setUser();
        $reservation = Reservation::factory()
            ->create();
        $data = array_merge($reservation->toArray());
        $data['id'] = $reservation->id;

        $response = $this->put(route(self::MODEL . '.update', ['modelVariable' => $reservation->id]), $data);

        $response->assertUnauthorized();
    }

    public function test_delete_need_admin()
    {
        $this->setUser();
        $reservation = Reservation::factory()
            ->create();

        $response = $this->delete(route(self::MODEL . '.destroy', ['modelVariable' => $reservation->id]));

        $response->assertUnauthorized();
    }

    public function test_undelete_need_admin()
    {
        $this->setUser();
        $reservation = Reservation::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.undelete', ['modelVariable' => $reservation->id]));

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

        $reservation = Reservation::factory()
            ->make();
        $data = array_merge($reservation->toArray());

        $response = $this->post(route(self::MODEL . '.store'), $data);

        $response->assertSessionHas('ok');
    }

    public function test_edit()
    {
        $this->setUser('admin');

        $reservation = Reservation::factory()
            ->create();

        $response = $this->get(route(self::MODEL . '.edit', ['modelVariable' => $reservation->id]));

        $response->assertStatus(200);
    }

    public function test_update()
    {
        $this->setUser('admin');

        $reservation = Reservation::factory()
            ->create();
        $data = array_merge($reservation->toArray());

        $response = $this->put(route(self::MODEL . '.update', ['modelVariable' => $reservation->id]), $data);
        $modelVariable = Reservation::find($reservation->id);

        $this->assertNotNull($reservation->user_id_modification);
        $response->assertSessionHas('ok');
    }

    public function test_delete()
    {
        $this->setUser('admin');

        $reservation = Reservation::factory()
            ->create();

        $response = $this->delete(route(self::MODEL . '.destroy', ['modelVariable' => $reservation->id]));

        $this->assertSoftDeleted(Reservation::class);
        $response->assertSessionHas(['ok']);
    }

    public function test_undelete()
    {
        $this->setUser('admin');

        $reservation = Reservation::factory()
            ->create();

        $response = $this->delete(route(self::MODEL . '.destroy', ['modelVariable' => $reservation->id]));
        $this->assertSoftDeleted(Reservation::class);
        $response->assertSessionHas(['ok']);

        $response = $this->get(route(self::MODEL . '.undelete', ['modelVariable' => $reservation->id]));

        $this->assertNull($reservation->user_id_suppression);
        $response->assertSessionHas(['ok']);
    }

    public function test_json()
    {
        $this->setUser('admin');

        $response = $this->get(route(self::MODEL . '.json'));

        $response->assertJsonStructure();
    }
}
