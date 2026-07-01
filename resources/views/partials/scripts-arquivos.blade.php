@once
  <script type="text/javascript">
    // essas variáveis precisam ser definidas aqui para que fiquem disponíveis no arquivos.js
    var max_upload_size = {{ $max_upload_size }};
    var classe_nome_plural = '{{ \App\Utils\ClasseUtils::obterClasseNomePlural($classe_nome) }}';
  </script>
  <script src="js/arquivos.js"></script>
@endonce
