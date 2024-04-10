<?php

namespace NickPotts\LibSql\Test;

use Illuminate\Support\Facades\DB;
use NickPotts\LibSql\Test\Models\User;

class LibSqlTest extends TestCase
{
    public function test_d1_database_select()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
    }

    public function test_d1_database_transaction()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        DB::transaction(function () {
            /** @var User $user */
            $user = factory(User::class)->create();
            $dbUser = User::whereEmail($user->email)->first();

            $this->assertEquals($user->id, $dbUser->id);

            return $dbUser;
        });
    }

    public function test_transactions_rolls_back()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);

        DB::beginTransaction();

        $user->delete();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);

        DB::rollBack();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);

    }
}
