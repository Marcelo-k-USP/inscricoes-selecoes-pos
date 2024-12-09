@extends('master')

@section('content')
@parent
  <div class="row">
    <div class="col-md-12">
      <div class="card mb-3">
        <div class="card-header">
          @can('perfiladmin')
            <a href="users">Usuários</a>
          @else
            Usuários
          @endcan
          <i class="fas fa-angle-right"></i> {{ $user->name }}
          @can('perfiladmin')
            | @include('users.partials.btn-change-user')
          @endcan
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              @if (!$user->local)
                <div><span class="text-muted">Número USP:</span> {{ $user->codpes }}</div>
              @endif
              <div><span class="text-muted">Nome:</span> {{ $user->name }}</div>
              <div><span class="text-muted">E-mail:</span> {{ $user->email }}</div>
              @if (!$user->local)
                <div>
                  <span class="text-muted">Vínculo:</span>
                  {{ $user->setores()->wherePivot('funcao', '!=', 'Gerente')->first()->pivot->funcao ?? 'sem vínculo' }} -
                  {{ $user->setores()->wherePivot('funcao', '!=', 'Gerente')->first()->sigla ?? 'sem setor' }}
                </div>
                <div><span class="text-muted">Telefone:</span> {{ $user->telefone }}</div>
              @else
                <div><span class="text-muted">Celular:</span> {{ $user->telefone }}</div>
              @endif
              <div><span class="text-muted">Último login:</span> {{ $user->last_login_at }}</div>
              @can('perfiladmin')
                <div><span class="text-muted">Admin:</span> {{ $user->is_admin ? 'sim' : 'não' }}</div>
              @endcan
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      @can('perfiladmin')
        @include('users.partials.card-oauth')
      @endcan
    </div>
  </div>
@endsection
