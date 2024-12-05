<?php

return [
    // Deprecado, remover no próximo release
    'admins' => env('SENHAUNICA_ADMINS'),

    'usar_replicado' => env('USAR_REPLICADO', true),
    'upload_max_filesize' => (int) env('UPLOAD_MAX_FILESIZE', '16') * 1024,

    // deprecado em 2/23. Remover no próximo release
    'forcar_https' => env('FORCAR_HTTPS', false),

    // reCAPTCHA
    'recaptcha_site_key' => env('RECAPTCHA_SITE_KEY'),
    'recaptcha_secret_key' => env('RECAPTCHA_SECRET_KEY'),
];
