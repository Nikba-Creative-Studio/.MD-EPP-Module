<?php

declare(strict_types=1);

return [
    'base_url' => $_ENV['EPP_BASE_URL'] ?? 'https://epp.nic.md/epp',
    'client_id' => $_ENV['EPP_CLIENT_ID'] ?? '',
    'password' => $_ENV['EPP_PASSWORD'] ?? '',
    'account' => $_ENV['EPP_ACCOUNT'] ?? '',
    'account_password' => $_ENV['EPP_ACCOUNT_PASSWORD'] ?? '',
    
    'transport' => [
        'connect_timeout' => (int) ($_ENV['CONNECT_TIMEOUT'] ?? 30),
        'read_timeout' => (int) ($_ENV['READ_TIMEOUT'] ?? 60),
        'retries' => (int) ($_ENV['RETRIES'] ?? 3),
        'verify_ssl' => filter_var($_ENV['VERIFY_SSL'] ?? 'true', FILTER_VALIDATE_BOOLEAN),
        'ca_bundle' => $_ENV['CA_BUNDLE'] ?? '',
        'user_agent' => $_ENV['USER_AGENT'] ?? 'MD-EPP-SDK/1.0',
    ],
    
    'logging' => [
        'enabled' => filter_var($_ENV['LOG_EPP'] ?? '1', FILTER_VALIDATE_BOOLEAN),
        'redact' => filter_var($_ENV['LOG_REDACT'] ?? '1', FILTER_VALIDATE_BOOLEAN),
        'retain_days' => (int) ($_ENV['LOG_RETAIN_DAYS'] ?? 14),
        'storage_path' => __DIR__ . '/../storage/logs/epp',
    ],
];
