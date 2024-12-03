<?php

namespace App\Http\Controllers;

use App\Services\ViacepService;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    protected $viacepService;

    public function __construct(ViacepService $viacepService)
    {
        $this->middleware('auth');
        $this->viacepService = $viacepService;
    }

    public function consultarCep(Request $request)
    {
        $result = $this->viacepService->consultarCep($request->input('cep'));
        if (isset($result['error']))
            return response()->json(['error' => $result['error']], 500);
        return response()->json($result);
    }
}
