<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\ImportService;
use Mockery;
use App\Repositories\ImportRepositoryInterface;
use Illuminate\Database\QueryException;

class ImportServiceTest extends TestCase
{
    /**
     * @var \Mockery\Mock|Repositories\ImportRepositoryInterface;
     */
    protected $importRepoMock;

    public function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            $this->importRepoMock = Mockery::mock(ImportRepositoryInterface::class);
        });

        parent::setUp();
    }

    public function testImportFileData()
    {
        $data = [
            'id' => 15,
            'name' => 'nam',
            'created_at' => '09/04/19',
        ];
        $service = new ImportService($this->importRepoMock);
        $this->importRepoMock->shouldReceive('insert')
            ->once()
            ->andReturn(true);
        $response = $service->importFile($data);
        $this->assertEquals($response, true);
    }
}
