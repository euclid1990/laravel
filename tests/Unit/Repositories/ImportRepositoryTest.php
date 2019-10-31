<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use Mockery as m;
use Symfony\Component\HttpFoundation\ParameterBag;
use Illuminate\Database\Query\Processors\Processor;
use App\Http\Requests\ImportFileCsvRequest;
use Illuminate\Support\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\SQLiteGrammar;
use Illuminate\Database\Connection;
use App\Repositories\ImportRepository;

class ImportRepositoryTest extends TestCase
{
    protected function makeRepository($connection)
    {
        return new ImportRepository($connection);
    }

    protected function getInstanceClass()
    {
        return \stdClass::class;
    }

    protected function mockDatabaseConnection()
    {
        $connection = m::mock(Connection::class);
        $connection->allows()
            ->table()
            ->with(m::any())
            ->andReturnUsing(function ($table) use ($connection) {
                return (new Builder(
                    $connection,
                    new SQLiteGrammar(),
                    new Processor()
                ))->from($table);
            });
        return $connection;
    }

    public function testInsertRepo()
    {
        $c = $this->mockDatabaseConnection();
        $repo = $this->makeRepository($c);
        $data = [
            'id' => 15,
            'name' => 'nam',
            'created_at' => '09/04/19',
        ];
        $c->shouldReceive('insert')
            ->once()
            ->withArgs([
                'insert into "imports" ("id", "name", "created_at") values (?, ?, ?)',
                array_values($data),
            ])
            ->andReturn(true);
        $result = $repo->insert($data);
        $this->assertEquals(true, $result);
    }
}
