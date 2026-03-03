@once
  <script type="text/javascript">
    // essas variáveis precisam ser definidas aqui para que fiquem disponíveis no arquivos.js
    var max_upload_size = {{ $max_upload_size }};
    var inscricao_ou_matricula = '{{ request()->segment(1) }}';
  </script>
  <script src="js/arquivos.js"></script>
@endonce
