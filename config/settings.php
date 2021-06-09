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
        ]);
    },
];
