<?php

namespace Tests\Unit\Http\Requests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\ImportFileCsvRequest;
use Symfony\Component\HttpFoundation\ParameterBag;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;
use App\Rules\CheckImportFile;
use Mockery;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ImportFileCsvRequestTest extends TestCase
{
    public function testRequestWithFileInvalidRow()
    {
        $ruleMock = Mockery::mock('overload:\App\Rules\CheckImportFile', '\Illuminate\Contracts\Validation\Rule');
        $data = [
            'file' => new UploadedFile(base_path('tests/files/data_check_row_rule.csv'), 'data_check_rule.csv', 'csv', null, null, true),
            ];
        $response = [
            'status' => false,
            'message' => trans('import.message.error_element', [
                'row' => '2',
            ]),
        ];

        $ruleMock->shouldReceive('passes')
            ->once()
            ->andReturn(false);
        $ruleMock->shouldReceive('message')
            ->twice()
            ->andReturn($response['message']);

        $request = new ImportFileCsvRequest();

        $request->setContainer($this->app)->setRedirector($this->app->make(Redirector::class));
        $this->app->instance('request', $request);
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        try {
            $request->validateResolved();
        } catch (ValidationException $e) {
            $this->assertValidatorExceptionContainErrorMsg($e, $response['message']);
        }
    }

    public function testRequestWithEmptyData()
    {
        $data = [];

        $request = new ImportFileCsvRequest();

        $request->setContainer($this->app)->setRedirector($this->app->make(Redirector::class));
        $this->app->instance('request', $request);

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        try {
            $request->validateResolved();
        } catch (ValidationException $e) {
            $message = trans('import.message.file_required');
            $this->assertEquals(Arr::get($e->errors(), 'file.0'), $message);
        }
    }

    public function testRequestWithFileSizeUpperBound()
    {
        $data = [
            'file' => UploadedFile::fake()->create('test.csv', 4097),
            ];
        $request = new ImportFileCsvRequest();

        $request->setContainer($this->app)->setRedirector($this->app->make(Redirector::class));
        $this->app->instance('request', $request);

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        try {
            $request->validateResolved();
        } catch (ValidationException $e) {
            $message = trans('import.message.file_max', [
                'max' => config('common.import.validation.file.max'),
                ]);
            $this->assertEquals(Arr::get($e->errors(), 'file.0'), $message);
        }
    }

    public function testRequestWithFileWrongExtension()
    {
        $data = [
            'file' => UploadedFile::fake()->create('test.png', 2000),
            ];
        $request = new ImportFileCsvRequest();

        $request->setContainer($this->app)->setRedirector($this->app->make(Redirector::class));
        $this->app->instance('request', $request);

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        try {
            $request->validateResolved();
        } catch (ValidationException $e) {
            $message = trans('import.message.file_mimes', [
                'format' => implode(', ', config('common.import.validation.file.type')),
                ]);
            $this->assertEquals(Arr::get($e->errors(), 'file.0'), $message);
        }
    }

    public function testRequestWithFileInvalidHeader()
    {
        $data = [
           'file' => new UploadedFile(base_path('tests/files/data_check_header_rule.csv'), 'data_check_rule.csv', 'csv', null, null, true),
            ];
        $request = new ImportFileCsvRequest();

        $request->setContainer($this->app)->setRedirector($this->app->make(Redirector::class));
        $this->app->instance('request', $request);

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        try {
            $request->validateResolved();
        } catch (ValidationException $e) {
            $message = trans('import.message.error_header');
            $this->assertEquals(Arr::get($e->errors(), 'file.0'), $message);
        }
    }

    public function testRequestWithValidData()
    {
        $data = [
            'file' => new UploadedFile(base_path('tests/files/data_check_rule.csv'), 'data_check_rule.csv', 'csv', null, null, true),
        ];
        $request = new ImportFileCsvRequest();

        $request->setContainer($this->app)->setRedirector($this->app->make(Redirector::class));
        $this->app->instance('request', $request);
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        $result = $request->validateResolved();
        $this->assertEquals($result, null);
    }
}
