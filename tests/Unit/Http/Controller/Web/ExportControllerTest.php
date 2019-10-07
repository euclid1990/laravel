<?php

namespace Tests\Unit\Http\Controller\Web;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use App\Services\ExportService;
use App\Http\Controllers\Web\ExportController;
use Symfony\Component\HttpFoundation\ParameterBag;
use Illuminate\Database\QueryException;
use App\Http\Requests\ExportFormRequest;
use App\Http\Resources\ExportResource;
use App\Models\Import;
use Illuminate\Http\Response;

class ExportControllerTest extends TestCase
{
    /**
     * @var \Mockery\Mock|Services\ExportService
     */
    protected $exportServiceMock;

    public function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            $this->exportServiceMock = Mockery::mock(ExportService::class);
        });

        parent::setUp();
    }

    public function testJsonExportWithValidData()
    {
        $controller = new ExportController($this->exportServiceMock);
        $request = new ExportFormRequest();
        $data = [
            'file_type' => 'csv',
            'separation' => ',',
            'encoding' => 'utf8',
            'export_column' => ['id', 'name'],
        ];
        $dataResponse = [
            'raw' => '1,thanh,2019-03-19,',
            'encoded' => '2,lam,2019-03-20,',
        ];
        $fileName = 'export.csv';
        $request->headers->set('content-type', 'application/json');
        $request->headers->set('accept', 'application/json');
        $request->setJson(new ParameterBag($data));
        $this->exportServiceMock->shouldReceive('makeFilename')
            ->once()
            ->andReturn($fileName);
        $this->exportServiceMock->shouldReceive('getData')
            ->once()
            ->andReturn($dataResponse);
        $result = $controller->export($request);
        $result = $result->toArray($request)['data'];
        $this->assertArraySubset(collect([
                'fileName' => $fileName,
                'fileMime' => 'text/csv',
                'fileDataBase64' => base64_encode($dataResponse['encoded']),
                'fileData' => $dataResponse['raw'],
        ]), collect($result));
    }

    public function testExportWithValidData()
    {
        $controller = new ExportController($this->exportServiceMock);
        $request = new ExportFormRequest();
        $data = [
            'file_type' => 'csv',
            'separation' => ',',
            'encoding' => 'utf8',
            'export_column' => ['id', 'name'],
        ];
        $dataResponse = [
            'raw' => '1,thanh,2019-03-19,',
            'encoded' => '2,lam,2019-03-20,',
        ];
        $fileName = 'export.csv';
        $request->merge($data);
        $this->exportServiceMock->shouldReceive('makeFilename')
            ->once()
            ->andReturn($fileName);
        $this->exportServiceMock->shouldReceive('getData')
            ->once()
            ->andReturn($dataResponse);
        $result = $controller->export($request);

        $this->assertTrue($result instanceof StreamedResponse);
    }
}
