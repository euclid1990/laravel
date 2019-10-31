<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use Mockery;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repositories\ExportRepository;
use App\Models\Import;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class ExportRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected $modelMock;

    public function setUp():void
    {
        $this->afterApplicationCreated(function () {
            $this->modelMock = Mockery::mock(Import::class);
        });
        parent::setUp();
    }
    public function testExportRepository()
    {
        $fields = [
            'id',
            'name',
        ];
        $repo = new ExportRepository($this->modelMock);

        $data = factory(Import::class)->make(['id' => 1]);
        $dataCollect = collect([$data]);
        $this->modelMock->shouldReceive('get')
            ->once()
            ->andReturn($dataCollect);

        $result = $repo->select($fields);
        $result = collect($result);

        $this->assertTrue($result->contains('id', $data->id));
    }
}
