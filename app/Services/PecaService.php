<?php

namespace App\Services;

use App\Models\Peca;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PecaService
{
   
    public function listar(): LengthAwarePaginator
    {
        return Peca::orderBy('id_peca')->paginate(15);
    }

    public function buscar(int $id): Peca
    {
        return Peca::findOrFail($id);
    }

    public function cadastrar(array $dados): Peca
    {
        return Peca::create($dados);
    }

    public function atualizar(Peca $peca, array $dados): Peca
    {
        $peca->update($dados);

        return $peca->refresh();
    }

    public function excluir(Peca $peca): void
    {
        $emUso = DB::table('ordem_servico_peca')
            ->where('peca_id', $peca->id_peca)
            ->exists();

        if ($emUso) {
            throw ValidationException::withMessages([
                'peca' => 'Esta peca esta vinculada a uma ordem de servico e nao pode ser excluida.'
            ]);
        }

        $peca->delete();
    }
}
