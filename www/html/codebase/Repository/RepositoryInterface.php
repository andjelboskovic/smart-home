<?php

namespace SmartHome\Repository;

use SmartHome\Persistence\Entity;
use SmartHome\Repository\Utilities\SortablePageableDto;

interface RepositoryInterface
{
    public function getById($id);

    public function getByIds(array $ids, ?SortablePageableDto $sort = null): array;

    public function countAll();

    public function getAll($limit = null, $offset = null, $orderBy = null);

    public function iterateAll(): iterable;

    public function getTableName(): string;

    public function getEntityClass(): string;

    public function save(Entity $entity, $updateId = false);

    public function delete(Entity $entity);

    public function replace(Entity $entity);

    public function findBy($criteria = null, $dto = null, $customCriteria = null);

    public function findOneBy($criteria = null, $dto = null, $customCriteria = null);

    public function countBy($criteria = null, $customCriteria = null);

    public function getPdoConnection();
}
