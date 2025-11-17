Olá {{ $user->name }},<br />
<br />
Você completou sua inscrição para {{ ($inscricao->selecao->categoria->nome != 'Aluno Especial') ? 'o processo seletivo ' . $inscricao->selecao->nome : 'aluno especial' }}.<br />
@if ($arquivos_count == 1)
  Não deixe de pagar o boleto que segue em anexo.<br />
@else
  Não deixe de pagar o(s) boleto(s) que segue(m) em anexo.<br />
@endif
<br />
@foreach ($arquivos_erro as $arquivo_erro)
  {!! $arquivo_erro !!}<br />
@endforeach
@include('emails.rodape')
