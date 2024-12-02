<?php

namespace App\Http\Controllers;

use App\Services\CorreiosService;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    protected $correiosService;

    public function __construct(CorreiosService $correiosService)
    {
        $this->correiosService = $correiosService;
    }

    public function consultarCep(Request $request)
    {
        $result = $this->correiosService->consultarCep($request->input('cep'));
        if (isset($result['error']))
            return response()->json(['error' => $result['error']], 500);
        return response()->json($result);
    }
}
