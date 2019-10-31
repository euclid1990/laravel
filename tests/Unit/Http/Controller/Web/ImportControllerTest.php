<?php

namespace Tests\Unit\Http\Controller\Web;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Http\Controllers\Web\ImportController;
use App\Http\Requests\ImportFileCsvRequest;
use App\Services\ImportService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\User;
use Mockery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class ImportControllerTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * @var \Mockery\Mock|Services\ImportService
     */
    protected $importServiceMock;

    public function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            $this->importServiceMock = Mockery::mock(ImportService::class);
        });

        parent::setUp();
    }

    public function testIndexReturnView()
    {
        $controller = new ImportController($this->importServiceMock);

        $user = factory(User::class, 1)->make([
            'id' => 1,
        ]);
        Auth::shouldReceive('user')->once()->andReturn($user);

        $view = $controller->index();

        $this->assertEquals('imports.index', $view->getName());
        $this->assertArraySubset(['user' => $user], $view->getData());
    }

    public function testImportValidData()
    {
        $controller = new ImportController($this->importServiceMock);
        $request = new ImportFileCsvRequest();
        $data = [
            'id' => '12',
            'name' => 'abcdef',
        ];
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));
        $this->importServiceMock->shouldReceive('importFile')
            ->once()
            ->andReturn(true);

        $response = $controller->import($request);

        $this->assertEquals(trans('import.message.success'), $response->getSession()->get('message'));
    }

    public function testImportEmptyData()
    {
        $controller = new ImportController($this->importServiceMock);
        $request = new ImportFileCsvRequest();
        $data = [];
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));
        $this->importServiceMock->shouldReceive('importFile')
            ->once()
            ->andReturnUsing(function () {
                throw new QueryException('', [], new \Exception);
            });

        $response = $controller->import($request);

        $this->assertEquals(null, $response->getSession()->get('message'));
    }

    public function testImportReturnQueryExeption()
    {
        $controller = new ImportController($this->importServiceMock);
        $request = new ImportFileCsvRequest();

        $data = [
            'id' => '1',
            'name' => 'abcdef',
        ];

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        $this->importServiceMock->shouldReceive('importFile')
            ->once()
            ->andReturnUsing(function () {
                throw new QueryException('', [], new \Exception);
            });

        $response = $controller->import($request);
        $errors = $response->getSession()->get('errors')->getMessages();

        $this->assertArrayHasKey('errors', $response->getSession()->get('errors')->messages());
        $this->assertEquals(trans('import.message.import_failed'), $errors['errors'][0]);
    }
}
