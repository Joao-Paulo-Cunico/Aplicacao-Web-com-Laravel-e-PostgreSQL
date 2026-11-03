<?php

namespace App\Http\Controllers;

use App\Models\Peca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PecaController extends Controller
{
    public function index()
    {
        $pecas = Peca::orderBy('id_peca')->paginate(15);

        return view('pecas.index', compact('pecas'));
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
            ->with('success', 'Peca cadastrada com sucesso!');
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
            ->with('success', 'Peca atualizada com sucesso!');
    }

       public function destroy(Peca $peca)
    {
        $emUso = DB::table('ordem_servico_peca')
            ->where('peca_id', $peca->id_peca)
            ->exists();

        if ($emUso) {
            return redirect()
                ->route('pecas.index')
                ->with('error', 'Esta peca esta vinculada a uma ordem de servico e nao pode ser excluida!');
        }

        $peca->delete();

        return redirect()
            ->route('pecas.index')
            ->with('success', 'Peca excluida com sucesso!');
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
