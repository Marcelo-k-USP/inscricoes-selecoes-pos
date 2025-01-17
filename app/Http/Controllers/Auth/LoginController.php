<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setor;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('senhaunica')->redirect();
    }

    public function handleProviderCallback()
    {
        // atualiza o banco local com os dados do replicado... assim, se houver atualizações na USP, serão refletidas aqui
        $userSenhaUnica = Socialite::driver('senhaunica')->user();
        $user = User::obterOuCriarPorCodpes($userSenhaUnica->codpes);
        $user->telefone = $userSenhaUnica->telefone;
        $user->email = $userSenhaUnica->email;
        $user->name = $userSenhaUnica->nompes;

        // permissions do senhaunica-socialite v3... por enquanto está false, pois está dando conflito
        if (config('senhaunica.permission')) {

            // garantindo que as permissions existam
            $permissions = ['admin', 'gerente', 'user'];
            foreach ($permissions as $permission)
                Permission::findOrCreate($permission);

            // vamos verificar no config se o usuário é admin
            if (in_array($userSenhaUnica->codpes, config('senhaunica.admins')))
                $user->givePermissionTo('admin');

            // vamos verificar no config se o usuário é gerente
            if (in_array($userSenhaUnica->codpes, config('senhaunica.gerentes')))
                $user->givePermissionTo('gerente');

            // default
            $user->givePermissionTo('user');
        }

        // vamos manter a configuracao antiga para compatibilidade retroativa
        // mas deverá ser ajustado e removido as referências a "is_admin"
        // vamos verificar no config se o usuário é admin
        if (in_array($userSenhaUnica->codpes, config('senhaunica.admins')))
            $user->is_admin = true;

        $user->last_login_at = now();
        $user->save();

        // vincula a pessoa ao setor
        foreach ($userSenhaUnica->vinculo as $vinculo)
            if ($setor = Setor::where('cod_set_replicado', $vinculo['codigoSetor'])->first())
                Setor::vincularPessoa($setor, $user, $vinculo['nomeVinculo']);

        Auth::login($user, true);
        if (Gate::allows('admin'))
            session(['perfil' => 'admin']);
        elseif (Gate::allows('gerente'))
            session(['perfil' => 'gerente']);
        else
            session(['perfil' => 'usuario']);
        return redirect('/inscricoes');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
