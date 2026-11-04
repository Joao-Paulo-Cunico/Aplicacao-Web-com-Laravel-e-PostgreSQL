<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PecaApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Garante que os testes nao mexam no Neon
        if (
            config('database.default') !== 'sqlite' ||
            config('database.connections.sqlite.database') !== ':memory:'
        ) {
            throw new \RuntimeException(
                'Os testes devem usar SQLite em memoria!'
            );
        }

        // Cria a tabela temporaria somente para os testes
        Schema::create('peca', function (Blueprint $table) {
            $table->increments('id_peca');
            $table->string('nome', 150);
            $table->string('descricao', 500);
            $table->decimal('preco_custo', 10, 2);
            $table->decimal('preco_venda', 10, 2);
            $table->integer('estoque')->default(0);
            $table->boolean('ativo')->default(true);
        });
    }

    // TESTE 1 - Cadastro de peca valida
    public function test_cadastra_peca_com_sucesso(): void
    {
        $dados = [
            'nome' => 'Filtro de Oleo Teste',
            'descricao' => 'Peca criada no teste automatizado',
            'preco_custo' => 25.00,
            'preco_venda' => 45.00,
            'estoque' => 10,
            'ativo' => true,
        ];

        $resposta = $this->postJson('/api/pecas', $dados);

        $resposta->assertStatus(201);

        $resposta->assertJsonPath(
            'data.nome',
            'Filtro de Oleo Teste'
        );

        $this->assertDatabaseHas('peca', [
            'nome' => 'Filtro de Oleo Teste',
            'estoque' => 10,
        ]);
    }

    // TESTE 2 - Impedir estoque negativo
    public function test_impede_cadastro_com_estoque_negativo(): void
    {
        $dados = [
            'nome' => 'Peca Invalida',
            'descricao' => 'Teste de validacao do estoque',
            'preco_custo' => 20.00,
            'preco_venda' => 35.00,
            'estoque' => -5,
            'ativo' => true,
        ];

        $resposta = $this->postJson('/api/pecas', $dados);

        $resposta->assertStatus(422);

        $resposta->assertJsonValidationErrors([
            'estoque',
        ]);

        $this->assertDatabaseCount('peca', 0);
    }
}
