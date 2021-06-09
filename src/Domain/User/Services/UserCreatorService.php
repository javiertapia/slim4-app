<?php
declare(strict_types=1);

namespace App\Domain\User\Services;

use App\Application\Exceptions\ValidationException;
use App\Domain\User\Repositories\UserCreatorRepository;

final class UserCreatorService
{
    private UserCreatorRepository $repository;

    public function __construct(UserCreatorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createUser(array $data): int
    {
        $this->validateNewUser($data);
        return $this->repository->insertUser($data);
    }

    private function validateNewUser(array $data): void
    {
        $errors = [];
        if (empty($data['username'])) {
            $errors['username'] = 'Input required';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Input required';
        } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Invalid email address';
        }

        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }
}
