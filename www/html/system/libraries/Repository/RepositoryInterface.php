<?php

namespace SmartHome\Repository;

use SmartHome\Persistence\Entity;

interface RepositoryInterface
{
    public function getById($id);

    public function getByIds(array $ids): array;

    public function getTableName(): string;

    public function getEntityClass(): string;

    public function save(Entity $entity, $updateId = false);

    public function findBy($criteria = null);

    public function findOneBy($criteria = null);
}
