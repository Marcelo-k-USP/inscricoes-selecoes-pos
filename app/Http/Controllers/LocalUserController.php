<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Hash;
use App\Http\Requests\LocalUserRequest;
use App\Mail\LocalUserMail;
use App\Models\LocalUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class LocalUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['showLogin', 'login', 'esqueceuSenha', 'iniciaRedefinicaoSenha', 'redefineSenha']);    // exige que o usuário esteja logado, exceto para showLogin, login, etc.
    }

    public function showLogin()
    {
        return view('localusers.login');
    }

    public function login(Request $request)
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
        if (!Auth::attempt($credentials))
            return processa_erro_login('Usuário e senha incorretos');

        return redirect('/inscricoes');
    }

    public function esqueceuSenha(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'O e-mail é obrigatório!',
            'email.email' => 'O e-mail não é válido!'
        ]);

        // procura por usuário local com esse e-mail (somente local... pois não queremos fornecer possibilidade de resetar senha única USP de um usuário não local)
        $localuser = User::where('email', $request->email)->where('local', '1')->first();
        if (is_null($localuser))
            processa_erro_login('E-mail não encontrado');

        // gera um token
        $token = Str::random(60);

        // armazena o token no banco de dados
        DB::table('password_resets')->updateOrInsert(
            ['email' => $localuser->email],    // procura por registro com este e-mail
            [                                  // atualiza ou insere com os dados abaixo
                'email' => $localuser->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // monta a URL de redefinição de senha
        $password_reset_url = url('localusers/redefinesenha', $token);

        // envia e-mail para o usuário local... não utilizo observer como no Chamados pois aqui não faz sentido, o observer faz mais sentido disparando seus eventos próprios (created, updated, etc.)
        \Mail::to($localuser->email)
            ->queue(new LocalUserMail(compact('localuser', 'password_reset_url')));

        request()->session()->flash('alert-success', 'E-mail enviado com sucesso');
        return view('localusers.login');
    }

    public function iniciaRedefinicaoSenha($token)
    {
        // verifica se o token recebido existe
        $password_reset = DB::table('password_resets')->get()->first(function ($reset) use ($token) {
            return Hash::check($token, $reset->token);
        });
        if (!$password_reset)
            processa_erro_login('Este link é inválido');

        // verifica se o token recebido expirou
        if (Carbon::parse($password_reset->created_at)->addMinutes(config('selecoes-pos.password_reset_link_expiry_time'))->isPast())
            processa_erro_login('Este link expirou');

        $email = $password_reset->email;
        return view('localusers.redefinesenha', compact('token', 'email'));
    }

    public function redefineSenha(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'min:8', 'confirmed', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
        ], [
            'password.required' => 'O campo de senha é obrigatório!',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres!',
            'password.confirmed' => 'A confirmação da senha não coincide.',
            'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial!',
        ]);

        // verifica se os dados vieram válidos
        $password_reset = DB::table('password_resets')->where('email', $request->email)->first();
        if ((!$password_reset) || (!Hash::check($request->token, $password_reset->token)))
            processa_erro_login('Este link é inválido');

        // verifica se o token recebido expirou
        if (Carbon::parse($password_reset->created_at)->addMinutes(config('selecoes-pos.password_reset_link_expiry_time'))->isPast())
            processa_erro_login('Este link expirou');

        // verifica se o usuário existe
        $user = User::where('email', $password_reset->email)
            ->where('local', '1')
            ->first();
        if (!$user)
            processa_erro_login('Usuário não cadastrado');

        // transaction para não ter problema de inconsistência do DB
        DB::transaction(function () use ($request, $user) {

            // atualiza a senha do usuário
            $user->password = Hash::make($request->password);
            $user->save();

            // remove o token de redefinição de senha da tabela
            DB::table('password_resets')->where('email', $request->email)->delete();
        });

        request()->session()->flash('alert-success', 'Senha redefinida com sucesso');
        return view('localusers.login');
    }

    private function processa_erro_login($msg)
    {
        request()->session()->flash('alert-danger', $msg);
        return view('localusers.login');
    }

    public function index(Request $request)
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

    public function store(LocalUserRequest $request)
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

    public function update(Request $request, User $localuser)
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

    public function destroy(User $localuser)
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
