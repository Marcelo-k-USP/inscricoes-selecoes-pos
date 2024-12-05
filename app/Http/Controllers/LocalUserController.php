<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use App\Http\Requests\LocalUserRequest;
use App\Models\LocalUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class LocalUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['showLogin', 'login']);    // exige que o usuário esteja logado, exceto para showLogin e login
    }

    function showLogin()
    {
        return view('localusers.login');
    }

    function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'O e-mail é obrigatório!',
            'email.email' => 'O e-mail não é válido!',
            'password.required' => 'A senha é obrigatória!'
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials))
            return redirect('/inscricoes');

        request()->session()->flash('alert-danger', 'Usuário e senha incorretos');
        return view('localusers.login');
    }

    function index(Request $request)
    {
        $this->authorize('admin');
        \UspTheme::activeUrl('localusers');

        $localusers = User::where('local', '1')->get();
        $fields = LocalUser::getFields();

        if ($request->ajax()) {
            // formatado para datatables
            #return response(['data' => $localusers]);
        } else {
            $modal['url'] = 'localusers';
            $modal['title'] = 'Editar Usuário Local';
            $rules = LocalUserRequest::rules;
            return view('localusers.index', compact('localusers', 'fields', 'modal', 'rules'));
        }
    }

    public function show(Request $request, $id)
    {
        #usando no ajax, somente para admin
        $this->authorize('admin');
        \UspTheme::activeUrl('localusers');

        if ($request->ajax()) {
            # preenche os dados do form de edição de um usuário local
            return User::where('id', $id)->where('local', 1)->first();
        } else {
            # desativado por enquanto
            return false;
            return view('localusers.show', compact('localuser'));
        }
    }

    function store(LocalUserRequest $request)
    {
        $this->authorize('admin');

        $validator = Validator::make($request->all(), LocalUserRequest::rules, LocalUserRequest::messages);
        if ($validator->fails())
            return back()->withErrors($validator)->withInput();
        if (User::emailExiste($request->email))
            return back()->withErrors(Validator::make([], [])->errors()->add('email', 'Este e-mail já está em uso!'))->withInput();

        $localuser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'local' => '1',
        ]);
        $localuser->givePermissionTo('user');

        \UspTheme::activeUrl('localusers');
        return view('localusers.index', $this->monta_compact());
    }

    function update(Request $request, User $localuser)
    {
        $this->authorize('admin');

        $validator = Validator::make($request->all(), LocalUserRequest::rules, LocalUserRequest::messages);
        if ($validator->fails())
            return back()->withErrors($validator)->withInput();

        $request->merge(['password' => Hash::make($request->password)]);
        $localuser->update($request->all());

        \UspTheme::activeUrl('localusers');
        return view('localusers.index', $this->monta_compact());
    }

    function destroy(User $localuser)
    {
        $this->authorize('admin');

        if ($localuser->local == false) {
            request()->session()->flash('alert-danger', 'Usuário senha única não pode ser apagado.');
            return redirect('/localusers');
        }
        $localuser->delete();

        \UspTheme::activeUrl('localusers');
        return view('localusers.index', $this->monta_compact());
    }

    private function monta_compact() {
        $localusers = User::where('local', '1')->get();
        $fields = LocalUser::getFields();
        $rules = LocalUserRequest::rules;

        return compact('localusers', 'fields', 'rules');
    }
}
