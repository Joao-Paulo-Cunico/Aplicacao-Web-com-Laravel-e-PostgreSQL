<?php

namespace Tests\Feature;

use Tests\TestCase;

class PaginasWebTest extends TestCase
{
    public function test_pagina_inexistente_retorna_404(): void
    {
        $response = $this->get('/pagina-que-nao-existe');

        $response->assertStatus(404);
    }

    public function test_rota_de_clientes_aceita_metodo_get(): void
    {
        $rota = app('router')
            ->getRoutes()
            ->getByName('clientes.index');

        $this->assertNotNull($rota);

        $this->assertContains('GET', $rota->methods());
    }
}
