<?php
declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use Monolog\Logger;

return [
    SettingsInterface::class => function () {
        return new Settings([
            'displayErrorDetails' => (bool)($_ENV['DISPLAY_ERROR_DETAILS'] ?? false), // Should be set to false in production
            'logError'            => (bool)($_ENV['LOG_ERRORS'] ?? false),
            'logErrorDetails'     => (bool)($_ENV['LOG_ERROR_DETAILS'] ?? false),
            'logger'              => [
                'name'  => 'slim-app',
                'path'  => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
            'db'     => [
                'driver'    => 'mysql',
                'host'      => $_ENV['DB_HOST'] ?? 'localhost',
                'database'  => $_ENV['DB_DATABASE'] ?? 'test',
                'username'  => $_ENV['DB_USERNAME'] ?? 'root',
                'password'  => $_ENV['DB_PASSWORD'] ?? '',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'flags'     => [
                    // Turn off persistent connections
                    PDO::ATTR_PERSISTENT         => false,
                    // Enable exceptions
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    // Emulate prepared statements
                    PDO::ATTR_EMULATE_PREPARES   => true,
                    // Set default fetch mode to array
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    // Set character set
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
                ],
            ],
        ]);
    },
];
