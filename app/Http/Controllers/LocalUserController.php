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
use Spatie\Permission\Models\Role;

class LocalUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['showLogin', 'login', 'esqueceuSenha', 'iniciaRedefinicaoSenha', 'redefineSenha', 'confirmaEmail']);    // exige que o usuário esteja logado, exceto para showLogin, login, etc.
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
            return $this->processa_erro_login('Usuário e senha incorretos');

        $localuser = Auth::user();
        if ($localuser->local && !$localuser->email_confirmado) {
            Auth::logout();

            request()->session()->flash('alert-danger', 'E-mail não confirmado');
            return back();
        }

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
            return $this->processa_erro_login('E-mail não encontrado');

        // gera um token e o armazena no banco de dados
        $token = Str::random(60);
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

    public function iniciaRedefinicaoSenha(string $token)
    {
        // verifica se o token recebido existe
        $password_reset = DB::table('password_resets')->get()->first(function ($reset) use ($token) {
            return Hash::check($token, $reset->token);
        });
        if (!$password_reset)
            return $this->processa_erro_login('Este link é inválido');

        // verifica se o token recebido expirou
        if (Carbon::parse($password_reset->created_at)->addMinutes(config('selecoes-pos.password_reset_link_expiry_time'))->isPast())
            return $this->processa_erro_login('Este link expirou');

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
            return $this->processa_erro_login('Este link é inválido');

        // verifica se o token recebido expirou
        if (Carbon::parse($password_reset->created_at)->addMinutes(config('selecoes-pos.password_reset_link_expiry_time'))->isPast())
            return $this->processa_erro_login('Este link expirou');

        // verifica se o usuário existe
        $user = User::where('email', $password_reset->email)
            ->where('local', '1')
            ->first();
        if (!$user)
            return $this->processa_erro_login('Usuário não cadastrado');

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

    public function confirmaEmail(string $token)
    {
        // verifica se o token recebido existe
        $email_confirmation = DB::table('email_confirmations')->get()->first(function ($confirmation) use ($token) {
            return Hash::check($token, $confirmation->token);
        });
        if (!$email_confirmation)
            return $this->processa_erro_confirmation('Este link é inválido');

        // loga automaticamente o usuário
        $localuser = User::where('email', $email_confirmation->email)->first();
        $localuser->givePermissionTo('user');
        $localuser->last_login_at = now();
        $localuser->email_confirmado = true;
        $localuser->save();
        Auth::login($localuser, true);
        session(['perfil' => 'usuario']);

        request()->session()->flash('alert-success', 'E-mail confirmado com sucesso<br />' .
            'Suba os arquivos necessários para a avaliação da sua inscrição<br />');

        \UspTheme::activeUrl('inscricoes');
        $inscricao = $localuser->inscricoes()->wherePivot('papel', 'Autor')->orderBy('user_inscricao.created_at', 'desc')->first();    // obtém a inscrição mais recente desse usuário
        return view('inscricoes.edit', (new InscricaoController())->monta_compact($inscricao, 'edit'));
    }

    private function processa_erro_login(string $msg)
    {
        request()->session()->flash('alert-danger', $msg);
        return view('localusers.login');
    }

    public function index(Request $request)
    {
        $this->authorize('localusers.viewAny');
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

    public function show(Request $request, string $id)
    {
        $this->authorize('localusers.view');
        \UspTheme::activeUrl('localusers');

        if ($request->ajax())
            return User::where('id', (int) $id)->where('local', 1)->first();    // preenche os dados do form de edição de um usuário local
    }

    public function store(LocalUserRequest $request)
    {
        $this->authorize('localusers.create');

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
        $this->authorize('localusers.update');

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
        $this->authorize('localusers.delete');

        if ($localuser->local == false) {
            request()->session()->flash('alert-danger', 'Usuário senha única não pode ser apagado.');
            return redirect('/localusers');
        }
        $localuser->delete();

        \UspTheme::activeUrl('localusers');
        return view('localusers.index', $this->monta_compact());
    }

    private function monta_compact()
    {
        $localusers = User::where('local', '1')->get();
        $fields = LocalUser::getFields();
        $rules = LocalUserRequest::rules;

        return compact('localusers', 'fields', 'rules');
    }
}
