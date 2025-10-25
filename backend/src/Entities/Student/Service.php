<?php

namespace App\Entities\Student;

use App\Utils\Exceptions\ExistingStudentException;
use App\System\Exceptions\NotFoundException;
use App\Utils\PasswordManager;
use App\Entities\Student\Filter;

class Service
{
    public function __construct(
        private readonly Repository $repository
    ) {}

    public function createNew(INPUT $data): void
    {   

        Validator::validate($data, null);

        if ($this->repository->emailOuCpfExiste($data->getEmail(), $data->getCpf())) {
            throw new ExistingStudentException();
        }

        $passwordHash = PasswordManager::passwordHash($data->getPassword());

        $student = new Entity(
            $data->getName(),
            $data->getBirth(),
            $data->getCpf(),
            $data->getEmail(),
            $passwordHash
        );

        $this->repository->createNew($student);
    }

    public function getAll(array $params, string $ordem): array
    {
        return $this->repository->getAll($params, $ordem, Filter::allowedFields());
    }

    public function getById(int $id): Entity
    {
        $student = $this->repository->getById($id);

        if (!$student) {
            throw new NotFoundException("Aluno {$id} não encontrado.");
        }

        return $student;
    }

    public function getByName(string $name): array
    {
        if (strlen($name) < 3) {
            throw new NotFoundException('Nome deve ter pelo menos 3 caracteres.');
        }

        return $this->repository->getByName($name);
    }

    public function update(int $id, INPUT $data): void
    {
        $emailOuCpfExiste = $this->repository->emailOuCpfExiste($data->getEmail(), $data->getCpf(), $id);

        if ($emailOuCpfExiste) {
            throw new ExistingStudentException();
        }

        Validator::validate($data, $id);

        $student = $this->repository->getById($id);

        if (!$student) {
            throw new NotFoundException("Aluno {$id} não encontrado.");
        }

        $student->setName($data->getName());
        $student->setBirth($data->getBirth());
        $student->setCpf($data->getCpf());
        $student->setEmail($data->getEmail());

        if (!empty($data->getPassword())) {
            $student->setPassword(PasswordManager::passwordHash($data->getPassword()));
        }

        $this->repository->update($student->getId(), [
            'name'       => $student->getName(),
            'birth' => $student->getBirth(),
            'cpf'        => $student->getCpf(),
            'email'      => $student->getEmail(),
            'password'      => $student->getPassword()
        ]);
    }

    public function remove(int $id): void
    {
        $student = $this->repository->getById($id);

        if (!$student) {
            throw new NotFoundException("Aluno {$id} não encontrado.");
        }

        $this->repository->remove($id);
    }
}
