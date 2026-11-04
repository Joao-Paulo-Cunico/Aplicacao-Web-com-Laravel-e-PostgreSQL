<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VeiculoController;
use App\Models\Veiculo;

class VeiculoWebTest extends TestCase
{
    public function test_rota_de_listagem_de_veiculos_esta_registrada(): void
    {
        $this->assertTrue(Route::has('veiculos.index'));

        $rota = Route::getRoutes()
            ->getByName('veiculos.index');

        $this->assertEquals('veiculos', $rota->uri());

        $this->assertContains('GET', $rota->methods());
    }

    public function test_controller_de_veiculos_possui_metodo_index(): void
    {
        $this->assertTrue(
            method_exists(VeiculoController::class, 'index')
        );
    }

    public function test_model_veiculo_utiliza_tabela_correta(): void
    {
        $veiculo = new Veiculo();

        $this->assertEquals(
            'oficina_mecanica.veiculo',
            $veiculo->getTable()
        );

        $this->assertEquals(
            'id_veiculo',
            $veiculo->getKeyName()
        );
    }
}
