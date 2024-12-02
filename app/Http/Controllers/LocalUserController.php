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

    function store(LocalUserRequest $request)
    {
        $this->authorize('admin');

        $validator = Validator::make($request->all(), LocalUserRequest::rules, LocalUserRequest::messages);
        if ($validator->fails())
            return back()->withErrors($validator)->withInput();

        /* garante que existe a permission para locais */
        $p = Permission::findOrCreate('Outros', 'senhaunica');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'codpes' => $request->codpes,
            'password' => Hash::make($request->password),
            'local' => '1',
        ]);
        $user->givePermissionTo($p);

        \UspTheme::activeUrl('localusers');
        $users = User::where('local', '1')->get();
        return view('localusers.index', ['users' => $users]);
    }

    function edit(User $user)
    {
        $this->authorize('admin');

        \UspTheme::activeUrl('localusers');
        return view('localusers.edit', ['user' => $user]);
    }

    function update(Request $request, User $user)
    {
        $this->authorize('admin');

        $validator = Validator::make($request->all(), LocalUserRequest::rules, LocalUserRequest::messages);
        if ($validator->fails())
            return back()->withErrors($validator)->withInput();

        $request->merge(['password' => Hash::make($request->password)]);
        $user->update($request->all());

        \UspTheme::activeUrl('localusers');
        $users = User::where('local', '1')->get();
        return view('localusers.index', ['users' => $users]);
    }

    function destroy(User $user)
    {
        $this->authorize('admin');

        if ($user->local == false) {
            request()->session()->flash('alert-danger', 'Usuário senha única não pode ser apagado.');
            return redirect('/localusers');
        }
        $user->delete();

        \UspTheme::activeUrl('localusers');
        $users = User::where('local', '1')->get();
        return view('localusers.index', ['users' => $users]);
    }
}
