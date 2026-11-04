<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;

class ClienteWebTest extends TestCase
{
    public function test_rota_de_listagem_de_clientes_esta_registrada(): void
    {
        $this->assertTrue(
            Route::has('clientes.index')
        );

        $rota = Route::getRoutes()
            ->getByName('clientes.index');

        $this->assertEquals(
            'clientes',
            $rota->uri()
        );

        $this->assertContains(
            'GET',
            $rota->methods()
        );
    }

    public function test_controller_de_clientes_possui_metodo_index(): void
    {
        $this->assertTrue(
            method_exists(
                \App\Http\Controllers\ClienteController::class,
                'index'
            )
        );
    }
}
