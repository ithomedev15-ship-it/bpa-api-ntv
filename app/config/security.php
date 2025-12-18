<?php

return [
    'url_key_secret' => $_ENV['URL_KEY_SECRET'] ?? 'CHANGE_THIS_SECRET',
    'url_key_expire' => 1000, // detik (5 menit)
];
