<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Team\Team;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\assertTrue;

class ProfileTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use RefreshDatabase;


    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['email' => 'example@test.com', 'name'=>'testUser']);
    }


    public function test_profile_index()
    {
        $response = $this->actingAs($this->user)->get('/login');
        // $response->assertStatus(200);
        $response->assertRedirect('');
    }

    public function test_profile_update()
    {
        $this->assertDatabaseHas('users', [
            'name' => 'testUser', 'id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put('/profile/update', [
            'name' => 'new name'    ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'name' => 'new name', 'id' => $this->user->id,
        ]);
    }

    public function test_profile_changepassword()
    {
        assertTrue(Hash::check('password', $this->user->password));

        $hashed_password = Hash::make('alma');
        $response = $this->actingAs($this->user)->put('/profile/changepassword', [
            'password' => $hashed_password ]);

        $response->assertStatus(302,'User Name Updated Successfully');

        $user = User::findOrFail($this->user->id);

        assertTrue(Hash::check('password', $user->password));
    }

    public function test_profile_destroy_has_team()
    {
        $response = $this->actingAs($this->user)->delete('/profile/destroy');
        $response->assertStatus(302, 'Deleting user only possible if you are no longer own any (archived) team(s)!');
        assertTrue($this->user->teams_owned_withArchived()->count()>0);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
        ]);

    }

    public function test_profile_destroy_has_no_team()
    {
        $user_id = $this->user->id;

        $team = $this->user->teams->first();
        $team->delete();
        $team->forceDelete();

        $response = $this->actingAs($this->user)->delete('/profile/destroy');
        $response->assertStatus(302);

        assertTrue(User::find($user_id) === null);
    }
}
