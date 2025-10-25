<?php

namespace App\Entities\User;

use App\Utils\Exceptions\ExistingUserException;
use App\Utils\Exceptions\InvalidUserException;
use App\Utils\PasswordManager;
use App\System\Exceptions\NotFoundException;

class Service
{
    public function __construct(
        private readonly Repository $repository,
    ) {}

    public function auth(string $email, string $password): Entity
    {
        $user = $this->repository->getEmail($email);

        if (!$user || !PasswordManager::password_verify($password, $user->getPassword())) {
            throw new InvalidUserException("Dados incorretos.");
        }

        return $user;
    }

    public function createNew(INPUT $data): void
    {
        Validator::validate($data);

        if ($this->repository->hasEmail($data->getEmail())) {
            throw new ExistingUserException('E-mail já cadastrado.');
        }

        $passwordHash = PasswordManager::passwordHash($data->getPassword());

        $user = new Entity(
            $data->getName(),
            $data->getEmail(),
            $passwordHash,
            null,
            $data->getRole()
        );

        $this->repository->createNew($user);
    }

    public function getAll(array $params, string $ordem): array
    {
        return $this->repository->getAll($params, $ordem, Filter::allowedFields());
    }

    public function getById(int $id): Entity
    {
        $user = $this->repository->getById($id);

        if (!$user) {
            throw new ExistingUserException();
        }

        return $user;
    }

    public function getEmail(string $email): ?Entity
    {
        $user = $this->repository->getEmail($email);

        if (!$user) {
            throw new NotFoundException("Usuário {$email} não encontrado.");
        }

        return $user;
    }

    public function update(int $id, INPUT $data): void
    {
        $emailExiste = $this->repository->hasEmail($data->getEmail(), $id);

        if ($emailExiste) {
            throw new ExistingUserException();
        }

        Validator::validate($data, $id);

        $user = $this->repository->getById($id);

        if (!$user) {
            throw new NotFoundException("Usuário {$id} não encontrado.");
        }

        $user->setName($data->getName());
        $user->setEmail($data->getEmail());

        if (!empty($data->getPassword())) {
            $user->setPassword(PasswordManager::passwordHash($data->getPassword()));
        }

        $this->repository->update($user->getId(), [
            'name'       => $user->getName(),
            'email'      => $user->getEmail(),
            'password'   => $user->getPassword()
        ]);
    }

    public function remove(int $id): void
    {
        $user = $this->repository->getById($id);

        if (!$user) {
            throw new NotFoundException("Usuário {$id} não encontrado.");
        }

        $this->repository->remove($id);
    }
}
