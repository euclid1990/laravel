<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Rules\CheckImportFile;
use App\Http\Requests\ImportFileCsvRequest;

class CheckImportFileTest extends TestCase
{
    // The function calls the private, protected method in the unit test
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function testRuleWithFileValidHeader()
    {
        $data = [
            0 => 'ID',
            1 => 'Name',
            2 => 'Created At',
        ];
        $request = new ImportFileCsvRequest();
        $rule = new CheckImportFile($request);
        $response = $this->invokeMethod($rule, 'isValidHeader', array($data));

        $this->assertEquals($response, true);
    }

    public function testRuleWithFileValidRow()
    {
        $columns = config('common.import.validation.file.header');
        $nextLine = [
            '17',
            'thanh',
            '19/09/03',
        ];
        $row = 1;
        $request = new ImportFileCsvRequest();
        $rule = new CheckImportFile($request);
        $response = $this->invokeMethod($rule, 'isValidRow', array($nextLine, $columns, $row));

        $this->assertArraySubset($response, [
            'status' => true,
            'message' => '',
        ]);
    }

    public function testRuleWithFileInvalidRow()
    {
        $columns = config('common.import.validation.file.header');
        $nextLine = [
            '17',
            'thanh',
        ];
        $row = 1;
        $request = new ImportFileCsvRequest();
        $rule = new CheckImportFile($request);
        $response = $this->invokeMethod($rule, 'isValidRow', array($nextLine, $columns, $row));

        $this->assertArraySubset($response, [
                'status' => false,
                'message' => trans('import.message.error_element', ['row' => $row]),
        ]);
    }

    public function testRuleWithFileInvalidRowColumns()
    {
        $columns = config('common.import.validation.file.header');
        $nextLine = [
            '17',
            str_repeat('a', config('common.import.validation.name.max') + 1),
            '19/09/03',
        ];
        $invalidColumn = 2;
        $row = 1;
        $request = new ImportFileCsvRequest();
        $rule = new CheckImportFile($request);
        $response = $this->invokeMethod($rule, 'isValidRow', array($nextLine, $columns, $row));
        $message = trans('import.message.error_row_name', [
            'row' => $row,
            'column' => $invalidColumn,
        ]);

        $this->assertArraySubset($response, [
            'status' => false,
            'message' => $message,
        ]);
    }
}
