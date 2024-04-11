<?php

namespace NickPotts\LibSql\Test;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use NickPotts\LibSql\Test\Models\User;
use Throwable;

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

    public function test_multi_level_transaction()
    {
        DB::beginTransaction();
        $user = factory(User::class)->create();
        DB::beginTransaction();
        $user2 = factory(User::class)->create();
        DB::commit();
        DB::commit();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user2->id,
        ]);
    }

    public function test_multi_level_transaction_rolls_back()
    {
        DB::beginTransaction();
        $user = factory(User::class)->create();
        DB::beginTransaction();
        $user2 = factory(User::class)->create();
        DB::commit();
        DB::rollBack();
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $user2->id,
        ]);
    }

    public function testTransactionIsRecordedAndCommitted()
    {
        $user = factory(User::class)->create();

        try {
            DB::transaction(function () use ($user) {
                try {
                    $user->update(['name' => 'jeff']);
                } catch (Exception $e) {
                    $this->fail('Transaction failed');
                }
            });
        } catch (Exception $e) {
            $this->fail('Transaction failed');
        }


        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'jeff',
        ]);
    }

    public function testTransactionIsRecordedAndCommittedUsingTheSeparateMethods()
    {
        $user = factory(User::class)->create();

        DB::beginTransaction();
        $user->update(['name' => 'test2']);
        DB::commit();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'test2',
        ]);
    }

    public function testNestedTransactionIsRecordedAndCommitted()
    {
        $user = factory(User::class)->create();

        $this->connection()->transaction(function () use ($user) {
            $user->update(['name' => 'test']);

            $this->connection()->transaction(function () use ($user) {
                $user->update(['name' => 'test2']);
            });
        });

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'test2',
        ]);
    }

    public function testNestedTransactionIsRecordeForDifferentConnectionsdAndCommitted()
    {
        $user = factory(User::class)->create();

        $this->connection()->transaction(function () use ($user) {
            $user->update(['name' => 'test']);


            $this->connection('second_connection')->transaction(function () use ($user) {
                $user->update(['name' => 'test2']);

                $this->assertDatabaseHas('users', [
                    'id' => $user->id,
                    'name' => 'test2',
                ]);

                $this->connection('second_connection')->transaction(function () use ($user) {
                    $user->update(['name' => 'test3']);
                });
            });
        });

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'test3',
        ]);
    }

    public function testTransactionIsRolledBack()
    {
        DB::table('users')->truncate();

        $user = factory(User::class)->create();

        $this->assertDatabaseCount('users', 1);

        try {
            DB::beginTransaction();
            factory(User::class)->create();
            $this->assertDatabaseCount('users', 2);

            throw new Exception;
        } catch (Throwable) {
            DB::rollBack();
        }

        $this->assertDatabaseCount('users', 1);
    }

    public function test_multi_level_transaction_sub_rolls_back()
    {
        DB::table('users')->truncate();
        DB::beginTransaction();
        $user = factory(User::class)->create();
        DB::beginTransaction();
        $user2 = factory(User::class)->create();
        DB::rollBack();
        DB::commit();

        $this->assertDatabaseCount('users', 1);
    }


    public function test_bad_connection()
    {
        $this->expectException(QueryException::class);
        DB::connection('libsql_bad')->select('SELECT * FROM users');
    }


    public function test_bad_sql_query()
    {
        $this->expectException(QueryException::class);
        DB::statement('ROFL');
    }

//    public function test_all_storage_types()
//    {
//        Schema::dropIfExists('all_types');
//        Schema::create('all_types', function (Blueprint $table) {
//            $table->id();
//            $table->bigInteger('integer');
//            $table->float('float');
//            $table->string('text');
//            $table->binary('blob');
//            $table->string('null')->nullable();
//            $table->timestamps();
//        });
//
//        $integer = PHP_INT_MAX;
//        $float = 3.1234567890123;
//        $text = 'Hello, World!
//        I am multi line text';
//        $blob = random_bytes(1024);
//
//        DB::table('all_types')->insert([
//            'integer' => $integer,
//            'float' => $float,
//            'text' => $text,
//            'blob' => $blob,
//            'null' => null,
//        ]);
//
//        $result = DB::table('all_types')->first();
//        $this->assertEquals($integer, $result->integer);
//        $this->assertEquals($float, $result->float);
//        $this->assertEquals($text, $result->text);
//        $this->assertEquals($blob, $result->blob);
//        $this->assertEquals(null, $result->null);
//
//        $this->assertIsInt($result->integer);
//        $this->assertIsFloat($result->float);
//        $this->assertIsString($result->text);
//        $this->assertIsString($result->blob);
//        $this->assertNull($result->null);
//
//
//        Schema::dropIfExists('all_types');
//    }


}
