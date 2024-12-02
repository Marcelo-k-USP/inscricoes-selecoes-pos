<?php

return [
    // Deprecado, remover no próximo release
    'admins' => env('SENHAUNICA_ADMINS'),

    'usar_replicado' => env('USAR_REPLICADO', true),
    'upload_max_filesize' => (int) env('UPLOAD_MAX_FILESIZE', '16') * 1024,

    // deprecado em 2/23. Remover no próximo release
    'forcar_https' => env('FORCAR_HTTPS', false),

    // token para consulta de CEP nos Correios
    'correios_api_token' => env('CORREIOS_API_TOKEN'),
];
