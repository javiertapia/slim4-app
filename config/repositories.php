<?php
declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use function DI\autowire;

return [
    UserRepository::class => autowire(InMemoryUserRepository::class),
];
