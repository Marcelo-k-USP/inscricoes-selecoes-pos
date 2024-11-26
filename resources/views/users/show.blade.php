@extends('master')

@section('content')
@parent
  <div class="row">
    <div class="col-md-12">
      <div class="card mb-3">
        <div class="card-header">
          @if (Gate::check('perfiladmin'))
            <a href="users">Usuários</a> <i class="fas fa-angle-right"></i>
          @else
            @if (Auth()->user()->is_admin)
              <a href="{{ route('senhaunica-users.index') }}">Users</a> <i class="fas fa-angle-right"></i>
            @else
              Meu perfil <i class="fas fa-angle-right"></i>
            @endif
          @endif
          {{ $user->name }}
          @if (Gate::check('perfiladmin'))
            | @include('users.partials.btn-change-user')
          @endif
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div><span class="text-muted">Número USP:</span> {{ $user->codpes }}</div>
              <div><span class="text-muted">Nome:</span> {{ $user->name }}</div>
              <div><span class="text-muted">email:</span> {{ $user->email }}</div>
              <div>
                <span class="text-muted">Vínculo:</span>
                {{ $user->setores()->wherePivot('funcao', '!=', 'Gerente')->first()->pivot->funcao ?? 'sem vínculo' }} -
                {{ $user->setores()->wherePivot('funcao', '!=', 'Gerente')->first()->sigla ?? 'sem setor' }}
              </div>
              <div><span class="text-muted">telefone:</span> {{ $user->telefone }}</div>
              <div><span class="text-muted">Último login:</span> {{ $user->last_login_at }}</div>
              @if (Gate::check('perfiladmin'))
                <div><span class="text-muted">Admin:</span> {{ $user->is_admin ? 'sim' : 'não' }}</div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      @includewhen(Gate::check('perfiladmin'), 'users.partials.card-oauth')
    </div>
  </div>
@endsection
