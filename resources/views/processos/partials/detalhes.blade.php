@if (count($processo->selecoes) > 0)
    <a href="#detalhes_{{ \Str::lower($processo->id) }}" class="btn btn-sm text-primary" data-toggle="collapse" role="button">
        <span class="badge badge-success">{{ count($processo->selecoes) }} seleções</span>
    </a>
@else
    &nbsp;
    <span class="badge badge-success">0 seleções</span>
@endif

<div class="ml-2 collapse" id="detalhes_{{ \Str::lower($processo->id) }}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div>
                        @if ($processo->selecoes && $processo->selecoes->isNotEmpty())
                            <b>Seleções</b><br>
                            <div class="ml-2">
                                @foreach ($processo->selecoes as $selecao)
                                    <a href="selecoes/{{ $selecao->id }}">{{ $selecao->nome }} <i class="fas fa-share"></i></a><br>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
