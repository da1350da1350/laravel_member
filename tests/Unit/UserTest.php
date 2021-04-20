<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateUser()
    {
        $user = User::factory()->unverified()->make();
        $user_array = $user->getAttributes();
        User::create($user_array);
        $this->assertDatabaseHas('users', $user_array);
    }

    public function testUpdateUser()
    {
        $user = $this->createUser();
        $old_user_name = $user->name;
        $user->name = strrev($user->name);
        $user->save();
        $this->assertDatabaseHas('users', $user->getAttributes());
    }

    public function testDeleteUser()
    {
        $user = $this->createUser();
        $user->delete();
        $fresh_user = DB::select('select * from users where id = ?', [$user->id]);
        $this->assertEmpty($fresh_user);
        $this->assertDatabaseMissing('users', $user->getAttributes());
    }

    public function createUser($unverified = false)
    {
        $user = User::factory()->create();
        if ($unverified) {
            $user->email_verified_at = null;
            $user->save();
        }
        $user_data = $user->getAttributes();
        $this->assertDatabaseHas('users', $user_data);
        return $user;
    }
}
