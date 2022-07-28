<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit;

        //o request->has verifica se o parametro foi definido
        if($request->has('filtro')){

            $condicoes = explode(':', $request->filtro);

            $pesquisa = Agendamento::where($condicoes[0],'like','%'.$condicoes[1].'%')->paginate($limit);

        }else{
            $pesquisa = Agendamento::paginate();
        }

        if($pesquisa == false){
            return response()->json(['error' => 'Pesquisa nao encontrada'], 403);
        }


        return response()->json($pesquisa, 201);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $regras = [

            'nome' => 'required|min:3',
            'telefone' => 'required',
            'favorito' => 'required|in:sim,nao',
            'data' => 'required'

        ];

        $feedback = [

            'required' => 'O campo :attribute e obrigatorio',
            'in' => 'O campo :attribute so pode ser sim ou nao',
            'min' => 'O campo :attribute deve te no minimo 4 Caracter'

        ];

        $request->validate($regras, $feedback);

        $agendamento = Agendamento::create([

            'nome' => $request->nome,
            'telefone' => $request->telefone,
            'favorito' => $request->favorito,
            'data' => $request->data
        ]);

        return response()->json($agendamento, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $agendamento = Agendamento::find($id);

        if($agendamento == false){
            return response()->json(['error' => 'Pesquisa nao encontrada'], 403);
        }

        return response()->json($agendamento, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $regras = [

            'favorito' => 'required|in:sim,nao',

        ];

        $feedback = [

            'required' => 'O campo :attribute e obrigatorio',
            'in' => 'O campo :attribute so pode ser sim ou nao',
            'min' => 'O campo :attribute deve te no minimo 4 Caracter'

        ];

        $request->validate($regras, $feedback);

        $agendamento = Agendamento::find($id);

        if($agendamento == false){
            return response()->json(['error' => 'Agendamento não encontrado impossivel fazer alteração'], 403);
        }

        $agendamento->update([
            'nome' => ($request->nome) ? $request->nome : $agendamento->nome,
            'telefone' => ($request->telefon) ? $request->telefon : $agendamento->telefone,
            'favorito' => ($request->favorito) ? $request->favorito : $agendamento->favorito,
            'data' => ($request->data) ? $request->data : $agendamento->data
        ]);

        return response()->json($agendamento, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $agendamento = Agendamento::find($id);

        if($agendamento == false){
            return response()->json(['error' => 'Agendamento não encontrado impossivel Deletar'], 403);
        }
        $agendamento->delete();

        return response()->json(['msg' => 'Agendamento deletado'], 201);
    }

}
