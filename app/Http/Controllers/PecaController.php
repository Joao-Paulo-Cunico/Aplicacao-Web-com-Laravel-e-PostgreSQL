<?php

namespace App\Http\Controllers;

use App\Models\Peca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PecaController extends Controller
{
    public function index(Request $request)
    {
        $pesquisa = trim((string) $request->query('pesquisa', ''));
        $situacao = $request->query('situacao', '');

        $pecas = Peca::query()
            ->when($pesquisa !== '', function ($query) use ($pesquisa) {
                $query->where(function ($q) use ($pesquisa) {
                    $q->where('nome', 'ilike', '%' . $pesquisa . '%')
                      ->orWhere('descricao', 'ilike', '%' . $pesquisa . '%');
                });
            })
            ->when(
                in_array($situacao, ['ativo', 'inativo'], true),
                function ($query) use ($situacao) {
                    $query->where('ativo', $situacao === 'ativo');
                }
            )
            ->orderBy('id_peca')
            ->paginate(30)
            ->withQueryString();

        return view('pecas.index', compact('pecas', 'pesquisa', 'situacao'));
    }

    public function create()
    {
        return view('pecas.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:150',
            'descricao' => 'required|string|max:500',
            'preco_custo' => 'required|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
        ]);

        $dados['ativo'] = true;

        Peca::create($dados);

        return redirect()
            ->route('pecas.index')
            ->with('success', 'Peça cadastrada com sucesso!');
    }

    public function show(Peca $peca)
    {
        //
    }

    public function edit(Peca $peca)
    {
        return view('pecas.edit', compact('peca'));
    }

    public function update(Request $request, Peca $peca)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:150',
            'descricao' => 'required|string|max:500',
            'preco_custo' => 'required|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'ativo' => 'required|boolean',
        ]);

        $peca->update($dados);

        return redirect()
            ->route('pecas.index')
            ->with('success', 'Peça atualizada com sucesso!');
    }

    public function destroy(Peca $peca)
    {
        $emUso = DB::table('ordem_servico_peca')
            ->where('peca_id', $peca->id_peca)
            ->exists();

        if ($emUso) {
            return redirect()
                ->route('pecas.index')
                ->with('error', 'Esta peça está vinculada a uma ordem de serviço e não pode ser excluída!');
        }

        $peca->delete();

        return redirect()
            ->route('pecas.index')
            ->with('success', 'Peça excluída com sucesso!');
    }

    public function reporEstoque(Request $request, Peca $peca)
    {
        $dados = $request->validate([
            'quantidade' => 'required|integer|min:1|max:10000',
        ]);

        DB::statement(
            'CALL oficina_mecanica.sp_repor_estoque(?, ?)',
            [$peca->id_peca, $dados['quantidade']]
        );

        return redirect()
            ->route('pecas.edit', $peca)
            ->with('success', 'Estoque atualizado com sucesso!');
    }
}
