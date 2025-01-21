@extends('master')

@section('styles')
@parent
  <style>
    #card-principal {
      border: 1px solid blue;
    }
    .bg-principal {
      background-color: LightBlue;
      border-top: 3px solid blue;
    }
    .disable-links {
      pointer-events: none;
    }
  </style>
@endsection

@section('content')
@parent
  <div class="row">
    <div class="col-md-12">
      <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-top">
          <div class="card-title my-0">
            Novo Cadastro
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-7">
              @include('localusers.show.card-principal')    {{-- Principal --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
