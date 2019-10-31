<?php

namespace Tests\Unit\Http\Requests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\ExportFormRequest;
use Symfony\Component\HttpFoundation\ParameterBag;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Arr;

class ExportFormRequestTest extends TestCase
{
    public function testRequestWithEmptyData()
    {
        $data = [];

        $request = new ExportFormRequest();

        $request->setContainer($this->app)->setRedirector($this->app->make(Redirector::class));
        $this->app->instance('request', $request);

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        try {
            $request->validateResolved();
        } catch (ValidationException $e) {
            $message = trans('export.message.file_type_required');
            $this->assertEquals(Arr::get($e->errors(), 'file_type.0'), $message);
        }
    }

    public function testRequestWithFiletypeWrong()
    {
        $data = [
            'file_type' => 'test',
        ];
        $request = new ExportFormRequest();

        $request->setContainer($this->app)->setRedirector($this->app->make(Redirector::class));
        $this->app->instance('request', $request);

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        try {
            $request->validateResolved();
        } catch (ValidationException $e) {
            $message = trans('export.message.file_type_in');
            $this->assertEquals(Arr::get($e->errors(), 'file_type.0'), $message);
        }
    }

    public function testRequestWithSeparationWrong()
    {
        $data = [
            'file_type' => 'csv',
            'separation' => '',
        ];
        $request = new ExportFormRequest();

        $request->setContainer($this->app)->setRedirector($this->app->make(Redirector::class));
        $this->app->instance('request', $request);

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        try {
            $request->validateResolved();
        } catch (ValidationException $e) {
            $message = trans('export.message.separation_required');
            $this->assertEquals(Arr::get($e->errors(), 'separation.0'), $message);
        }
    }

    public function testRequestWithEmptyEncoding()
    {
        $data = [
            'file_type' => 'csv',
            'separation' => ';',
            'encoding' => null,
        ];
        $request = new ExportFormRequest();

        $request->setContainer($this->app)->setRedirector($this->app->make(Redirector::class));
        $this->app->instance('request', $request);

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        try {
            $request->validateResolved();
        } catch (ValidationException $e) {
            $message = trans('export.message.encoding_required');
            $this->assertEquals(Arr::get($e->errors(), 'encoding.0'), $message);
        }
    }

    public function testRequestWithEncodingWrong()
    {
        $data = [
            'file_type' => 'csv',
            'separation' => ';',
            'encoding' => 'apple'
        ];
        $request = new ExportFormRequest();

        $request->setContainer($this->app)->setRedirector($this->app->make(Redirector::class));
        $this->app->instance('request', $request);

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        try {
            $request->validateResolved();
        } catch (ValidationException $e) {
            $message = trans('export.message.encoding_in');
            $this->assertEquals(Arr::get($e->errors(), 'encoding.0'), $message);
        }
    }

    public function testRequestWithExportcolumnWrong()
    {
        $data = [
            'file_type' => 'csv',
            'separation' => ';',
            'encoding' => 'utf8',
            'export_column' => null,
        ];
        $request = new ExportFormRequest();

        $request->setContainer($this->app)->setRedirector($this->app->make(Redirector::class));
        $this->app->instance('request', $request);

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        try {
            $request->validateResolved();
        } catch (ValidationException $e) {
            $message = trans('export.message.export_column_required');
            $this->assertEquals(Arr::get($e->errors(), 'export_column.0'), $message);
        }
    }

    public function testRequestWithValidData()
    {
        $data = [
            'file_type' => 'csv',
            'separation' => ';',
            'encoding' => 'utf8',
            'export_column' => ['id', 'name'],
        ];
        $request = new ExportFormRequest();

        $request->setContainer($this->app)->setRedirector($this->app->make(Redirector::class));
        $this->app->instance('request', $request);

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        $result = $request->validateResolved();
        $this->assertEquals($result, null);
    }
}
