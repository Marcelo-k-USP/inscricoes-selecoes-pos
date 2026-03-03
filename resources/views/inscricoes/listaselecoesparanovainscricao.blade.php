@extends('master')

@section('content')
@parent
  <div class="row">
    <div class="col-md-12 form-inline">
      <span class="h4 mt-2">Nova {{ ucfirst(\App\Utils\Nomenclatura::InscricaoOuMatricula()) }}</span>
      @include('partials.datatable-filter-box', ['otable'=>'oTable'])
    </div>
  </div>

  @php
    $existem_selecoes = false;
    foreach ($categorias as $categoria)
      if ($categoria->selecoes->count() > 0) {
        $existem_selecoes = true;
        break;
      }
  @endphp

  @if ($existem_selecoes)
    <br />
    Deseja se {{ request()->segment(1) == 'inscricoes' ? 'inscrever para' : 'matricular em' }}:<br />
    <table class="table table-sm table-hover nova-inscricao display responsive" style="width: 100%;">
      <thead>
        <tr>
          <th style="border: none;"><span class="d-none">Seleções</span></td>
        </tr>
      </thead>
      <tbody>
        @foreach ($categorias as $categoria)
          @if ($categoria->selecoes->count())
            <tr>
              <td>
                {{ $categoria->nome }}
                @if ($categoria->nome !== 'Aluno Especial')
                  @foreach ($categoria->selecoes as $selecao)
                    <div class="ml-3">
                      {{ $selecao->nome }}
                      @if (!is_null($selecao->descricao))
                        - {{ $selecao->descricao }}
                      @endif
                      <br />
                      @foreach ($selecao->niveis as $nivel)
                        &nbsp; &nbsp; &nbsp;<a href="{{ request()->segment(1) }}/create/{{ $selecao['id'] }}/{{ $nivel->id }}">{{ $nivel->nome }}</a><br />
                      @endforeach
                    </div>
                  @endforeach
                @else
                  @foreach ($categoria->selecoes as $selecao)
                    <div class="ml-3">
                      <a href="{{ request()->segment(1) }}/create/{{ $selecao['id'] }}">{{ $selecao->nome }}
                        @if (!is_null($selecao->descricao))
                          - {{ $selecao->descricao }}
                        @endif
                      </a>
                    </div>
                  @endforeach
                @endif
                <br>
              </td>
            </tr>
          @endif
        @endforeach
      </tbody>
    </table>
  @else
    <br />
    No momento, não há períodos abertos para {{ request()->segment(1) == 'inscricoes' ? 'inscrições' : 'matrículas' }}.
  @endif
@endsection

@section('javascripts_bottom')
@parent
  <script type="text/javascript">
    $(document).ready(function() {
      oTable = $('.nova-inscricao').DataTable({
        dom:
          't',
          'paging': false,
          'sort': false
      });
    });
  </script>
@endsection
