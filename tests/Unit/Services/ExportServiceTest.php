<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\ExportRepositoryInterface;
use App\Services\ExportService;
use Mockery;
use Carbon\Carbon;
use App\Models\Import;
use App\Http\Requests\ExportFormRequest;
use Symfony\Component\HttpFoundation\ParameterBag;

class ExportServiceTest extends TestCase
{
    /**
     * @var \Mockery\Mock|Repositories\ExportRepositoryInterface;
     */
    protected $exportRepoMock;
    protected $exportServiceMock;

    public function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            $this->exportRepoMock = Mockery::mock(ExportRepositoryInterface::class);
            $this->exportServiceMock = Mockery::mock(ExportService::class);
        });

        parent::setUp();
    }

    public function testMakeFilenameWithValidData()
    {
        $data = 'csv';
        $service = new ExportService($this->exportRepoMock);

        $response = $service->makeFilename($data);
        $filename = 'Export-' . Carbon::now()->toDateTimeString() . '.' . $data;
        $this->assertEquals($response, $filename);
    }

    public function testGetDataCsvValid()
    {
        $fileType = 'csv';
        $service = new ExportService($this->exportRepoMock);
        $request = new ExportFormRequest();
        $data = [
            'separation' => "\\t",
            'encoding' => 'utf8',
            'export_column' => ['id', 'name'],
        ];
        $import = [
            [
                'id' => 1,
                'name' => 'thanh',
            ],
        ];
        $result = [
            'raw' => "ID\tName\r\n1\tthanh\r\n",
            'encoded' => "ID\tName\r\n1\tthanh\r\n",
        ];

        $this->exportRepoMock->shouldReceive('select')
            ->once()
            ->andReturn($import);

        $request->headers->set('content-type', 'application/json');
        $request->headers->set('accept', 'application/json');
        $request->setJson(new ParameterBag($data));
        $response = $service->getData($fileType, $request);

        $this->assertArraySubset($response, $result);
    }

    public function testGetDataExcelValid()
    {
        $fileType = 'xlsx';
        $service = new ExportService($this->exportRepoMock);
        $request = new ExportFormRequest();
        $data = [
            'separation' => ',',
            'encoding' => 'utf8',
            'export_column' => ['id', 'name'],
        ];
        $result = [
            'raw' => '',
            'encoded' => '',
        ];

        $request->headers->set('content-type', 'application/json');
        $request->headers->set('accept', 'application/json');
        $request->setJson(new ParameterBag($data));
        $response = $service->getData($fileType, $request);

        $this->assertArraySubset($response, $result);
    }

    public function testCsvWithValidData()
    {
        $service = new ExportService($this->exportRepoMock);
        $cfg = config('common.export');
        $separation = ';';
        $encoding = 'shiftjis';
        $exportColumn = ['name', 'created_at', 'updated_at'];

        $import = factory(Import::class, 10)->make()->toArray();
        $this->exportRepoMock->shouldReceive('select')
            ->once()
            ->andReturn($import);

        $response = $service->csv($cfg, $exportColumn, $separation, $encoding);

        $import = implode('', array_map(function ($data) use ($separation) {
            return implode($separation, $data) . "\r\n";
        }, $import));

        $raw = implode($separation, array_map(function ($column) {
            return trans('export.header_export.' . $column);
        }, $exportColumn)) . "\r\n" . $import;

        $encoded = implode($separation, array_map(function ($column) {
            return trans('export.header_export.' . $column);
        }, $exportColumn)) . "\r\n" . mb_convert_encoding($import, 'Shift-JIS', 'UTF-8');

        $this->assertArraySubset($response, [
            'raw' => $raw,
            'encoded' => $encoded,
        ]);
    }
}
