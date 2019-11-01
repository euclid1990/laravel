<?php

namespace Tests\Feature\Http\Controller\Web;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class ImportControllerTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseTransactions;

    public function testReturnViewIndex()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get(route('import.index'));

        $response->assertStatus(200);
        $response->assertViewIs('imports.index');
    }

    public function testReturnViewIndexInvalid()
    {
        $response = $this->get(route('import.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testImportWithFileContentInCorrect()
    {
        $user = factory(User::class)->create();
        $data = [
            'file' => new UploadedFile(base_path('tests/files/data_check_rule.csv'), 'data_check_rule.csv', 'csv', null, null, true),
        ];

        $response = $this->actingAs($user)->post(route('import.create'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('message', 'Data imported successfully !');
    }

    public function testImportWithFileHeaderInvalid()
    {
        $user = factory(User::class)->create();
        $data = [
            'file' => new UploadedFile(base_path('tests/files/data_check_row_rule.csv'), 'data_check_header_rule.csv', 'csv', null, null, true),
        ];

        $response = $this->actingAs($user)->post(route('import.create'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $response->assertSessionHasErrors('file');
    }
}
