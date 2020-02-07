<?php

namespace SmartHome\Repository;

use BaseQuery;
use Composition\DatabaseToEntityMapping;
use Exception;
use FluentLiteral;
use FluentPDO;
use PDO;
use PDOException;
use SelectQuery;
use SmartHome\Composition\ExtractHydrate\ExtractHydrate;
use SmartHome\Exception\PlatformException;
use SmartHome\Composition\CIDBFactory;
use SmartHome\Persistence\Entity;
use SmartHome\Repository\Utilities\SortablePageableDto;

abstract class AbstractRepository implements RepositoryInterface
{
	use ExtractHydrate;

	protected static $instance = null;

	const TABLE_NAME = null;
	const ENTITY_CLASS = null;

	/** @var  PDO */
	protected $conn;
	/** @var FluentPDO */
	protected $queryBuilder;

	public function __construct()
	{
		// Acquire connection
		$this->conn = CIDBFactory::getConnection();
		$this->queryBuilder = new FluentPDO($this->conn);
	}

	/**
	 * @return static
	 */
	public static function getInstance(): self
	{
		return new static();
	}

	public function getTableName(): string
	{
		return static::TABLE_NAME;
	}

	public function getEntityClass(): string
	{
		return static::ENTITY_CLASS;
	}

	public function save(Entity $entity, $updateId = false)
	{
		$dto = $this->toDatabaseDto($this->getEntityClass(), $entity->getDto());
		unset($dto['id']);

		$query = null;
		if (intval($entity->getId()) > 0 && !$entity->isForceInsert()) {
			$query = $this->queryBuilder->update($this->getTableName(), $dto, $entity->getId());
		} else {
			$query = $this->queryBuilder->insertInto($this->getTableName(), $dto);
		}
		try {
			$result = $query->execute();
		} catch (PDOException $ex) {
			throw new Exception($ex->getMessage() . " " . $query);
		}

		// Update object with ID of new row in DB
		if ($updateId && !(intval($entity->getId()) > 0 && !$entity->isForceInsert())) {
			$entity->setId($result);
		}

		return $result;
	}

	public function delete(Entity $entity)
	{
		if (empty($entity->getId())) {
			return true;
		}

		$query = $this->queryBuilder->delete($this->getTableName(), $entity->getId());
		try {
			$result = $query->execute();
		} catch (PDOException $ex) {
			throw new Exception($ex, $query);
		}

		return $result;
	}

	public function replace(Entity $entity, bool $generateNewId = false)
	{
		try {
			$dto = $this->toDatabaseDto($this->getEntityClass(), $entity->getDto());
			if ($generateNewId || empty($dto['id'])) {
				unset($dto['id']);
			}

			$fields = implode(',', array_map(function ($field) {
				return "`{$field}`";
			}, array_keys($dto)));

			$fieldPlaceholders = [];
			$fieldValues = [];
			foreach ($dto as $field => $value) {
				if ($value instanceof FluentLiteral) {
					/** @var FluentLiteral $value */
					$fieldPlaceholders[] = $value->__toString();
				} else {
					$fieldPlaceholders[] = ":{$field}";
					$fieldValues[$field] = $value;
				}
			}
			$fieldPlaceholders = implode(',', $fieldPlaceholders);

			$query = "REPLACE INTO `{$this->getTableName()}` ({$fields}) VALUES ({$fieldPlaceholders})";
			$sth = $this->conn->prepare($query);
			$status = $sth->execute($fieldValues);

			if ($status && ($generateNewId || empty($dto['id']))) {
				$entity->setId($this->conn->lastInsertId());
			}

			return $status;
		} catch (PDOException $ex) {
			throw new Exception($ex, $query ?? '');
		}
	}

	public function buildObject(array $dto): Entity
	{
		return $this->buildEntityObject($this->getEntityClass(), $dto);
	}

	public function buildArrayOfObjects(array $dtos): array
	{
		$repo = $this;
		return array_map(function ($data) use ($repo) {
			return $repo->buildObject($data);
		}, $dtos);
	}

	public function getById($id)
	{
		return $this->findOneBy(['id' => intval($id)]);
	}

	public function getByIds(array $ids, ?SortablePageableDto $sort = null): array
	{
		if (empty($ids)) {
			return [];
		}
		return $this->findBy(['id' => $ids], $sort);
	}

	public function countAll()
	{
		return $this->countBy();
	}

	public function getAll($limit = null, $offset = null, $orderBy = null)
	{
		$query = $this->queryBuilder->from($this->getTableName());
		if (isset($limit)) {
			$query->limit($limit);
		}

		if (isset($offset)) {
			$query->offset($offset);
		}

		if (isset($orderBy)) {
			$query->orderBy($orderBy);
		}

		$result = [];
		foreach ($query->fetchAll() as $dto) {
			$result[$dto['id']] = $this->buildObject($dto);
		}
		return $result;
	}

	protected function iterate(array $criteria = []): iterable
	{
		$lastId = 0;
		$sort = new SortablePageableDto(1, 25, ['id' => 'asc']);
		while (true) {
			$objects = $this->findBy(
				array_merge($criteria, ['id > ?' => $lastId]), $sort
			);
			if (empty($objects)) {
				return;
			}

			foreach ($objects as $object) {
				$lastId = $object->getId();
				yield $object;
			}
		}
	}

	public function iterateAll(): iterable
	{
		return $this->iterate();
	}

	public function startDebug()
	{
		$this->queryBuilder->debug = function (BaseQuery $fluentQuery) {
			print_r($fluentQuery->getQuery());
			echo "\n\n";
			print_r($fluentQuery->getParameters());
			die();
		};
	}

	public function findBy($criteria = null, $dto = null, $customCriteria = null)
	{
		$query = $this->buildQueryFromCriteria($criteria);

		$query->groupBy("{$this->getBaseTableAlias()}.id");

		if (!empty($dto)) {
			$this->applySortingAndPagination($query, $dto);
		}

		try {
			$results = $query->fetchAll();
		} catch (PDOException $ex) {
			throw new Exception($ex, $query);
		}

		$objects = [];
		foreach ($results as $result) {
			$objects[$result['id']] = $this->buildObject($result);
		}
		return $objects;
	}

	public function findOneBy($criteria = null, $dto = null, $customCriteria = null)
	{
		if (empty($dto)) {
			$dto = new SortablePageableDto(1, 1);
		} else {
			$dto->setPage(1);
			$dto->setPerPage(1);
		}
		$results = $this->findBy($criteria, $dto, $customCriteria);
		return array_shift($results);
	}

	public function countBy($criteria = null, $customCriteria = null): int
	{
		return $this->countByColumn('id', $criteria, $customCriteria);
	}

	public function countByColumn(string $columnName, $criteria = null, $customCriteria = null): int
	{
		$query = $this->buildQueryFromCriteria($criteria);
		$this->updateQueryCustomCriteria($query, $customCriteria);
		$query->select(null)->select("COUNT(DISTINCT {$this->getBaseTableAlias()}.{$columnName})");

		try {
			$count = $query->fetchColumn();
		} catch (PDOException $ex) {
			throw new Exception($ex, $query);
		}
		return (int)$count;
	}

	protected function getBaseTableAlias(): string
	{
		return 'a0';
	}

	protected function buildQueryFromCriteria($criteria): SelectQuery
	{

		$baseTableName = $this->getTableName();
		$baseTableAlias = $this->getBaseTableAlias();
		$joins = [];
		$where = [];
		$aliasNumber = 0;
		if (!empty($criteria)) {
			foreach ($criteria as $field => $value) {
				$aField = explode('.', $field);
				$currentAlias = $baseTableAlias;

				if ($aField[0] !== $baseTableName) {
					array_unshift($aField, $baseTableName);
				}

				while (count($aField) > 2) {
					$leftTable = trim(array_shift($aField));
					$rightTable = trim($aField[0]);

					if (isset($joins["{$leftTable}|{$rightTable}"])) {
						$currentAlias = $joins["{$leftTable}|{$rightTable}"]['rightTableAlias'];
					} else {
						$aliasNumber++;
						$newAlias = "a{$aliasNumber}";
						$join = [
							'leftTable' => $leftTable,
							'leftTableAlias' => $currentAlias,
							'rightTable' => $rightTable,
							'rightTableAlias' => $newAlias
						];
						$currentAlias = $newAlias;

						$leftEntity = DatabaseToEntityMapping::getEntityForTable($leftTable);
						if (empty($leftEntity)) {
							throw new PlatformException("Looks like table '{$leftTable}' does not have related entity in DatabaseToEntityMapping class.");
						}
						$leftFieldsInfo = $leftEntity::getEntityFields();
						$leftJoinInfo = $leftEntity::getJoinedTablesAndFields();


						$rightEntity = DatabaseToEntityMapping::getEntityForTable($rightTable);
						if (empty($rightEntity)) {
							throw new PlatformException("Looks like table '{$rightEntity}' does not have related entity in DatabaseToEntityMapping class.");
						}
						$rightFieldsInfo = $rightEntity::getEntityFields();
						$rightJoinInfo = $rightEntity::getJoinedTablesAndFields();

						$leftTableJoinField = null;
						$rightTableJoinField = null;
						$possibleJoinFields = [
							[
								'id',
								!empty($rightJoinInfo[$leftTable]) ? $rightJoinInfo[$leftTable]['field'] : "{$leftTable}_id"
							],
							[
								!empty($leftJoinInfo[$rightTable]) ? $leftJoinInfo[$rightTable]['field'] : "{$rightTable}_id",
								'id'
							]
						];
						foreach ($possibleJoinFields as $fields) {
							if (in_array($fields[0], array_keys($leftFieldsInfo)) && in_array($fields[1],
									array_keys($rightFieldsInfo))
							) {
								$leftTableJoinField = $fields[0];
								$rightTableJoinField = $fields[1];
							}
						}
						if (empty($leftTableJoinField) || empty($rightTableJoinField)) {
							throw new PlatformException("No joining information provided to join '{$leftTable}' table with '{$rightTable}' table.");
						}
						$join['leftTableJoinField'] = $leftTableJoinField;
						$join['rightTableJoinField'] = $rightTableJoinField;
						$joins["{$leftTable}|{$rightTable}"] = $join;
					}
				}
				$where[] = [
					'field' => "{$currentAlias}.{$aField[1]}",
					'value' => $value
				];
			}
		}

		$query = $this->buildQuery();
		foreach ($joins as $join) {
			$query->leftJoin("{$join['rightTable']} {$join['rightTableAlias']} ON ({$join['rightTableAlias']}.{$join['rightTableJoinField']} = {$join['leftTableAlias']}.{$join['leftTableJoinField']})");
		}
		foreach ($where as $w) {
			$query->where($w['field'], $w['value']);
		}

		return $query;
	}

	protected function buildQuery(): SelectQuery
	{
		return $this->queryBuilder->from("{$this->getTableName()} {$this->getBaseTableAlias()}")->disableSmartJoin();
	}

	public function __call($name, $arguments)
	{
		$methods = ['findBy', 'findOneBy', 'countBy'];
		foreach ($methods as $method) {
			if (0 === strpos($name, $method)) {
				$field = substr($name, strlen($method));
				$field = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $field)), '_');

				$entity = DatabaseToEntityMapping::getEntityForTable($this->getTableName());
				if (empty($entity)) {
					throw new PlatformException("Looks like table '{$this->getTableName()}' does not have related entity in DatabaseToEntityMapping class.");
				}
				$fieldsInfo = $entity::getEntityFields();
				if (!in_array($field, array_keys($fieldsInfo))) {
					throw new PlatformException("Unknown field '{$field}' in table '{$this->getTableName()}'.");
				}

				if (count($arguments) > 1) {
					$field = "{$field} {$arguments[1]} ?";
				}
				$value = $arguments[0];
				return $this->$method([$field => $value]);
			}
		}

		trigger_error("Call to undefined method " . get_class($this) . "::{$name}()", E_USER_ERROR);
	}

	public function getPdoConnection(): PDO
	{
		return $this->conn;
	}

	public function applySorting(SelectQuery $query, SortablePageableDto $dto)
	{
		if (!empty($dto->getOrderBy())) {
			foreach ($dto->getOrderBy() as $field => $direction) {
				$query->orderBy("{$field} {$direction}");
			}
		}
	}

	public function applyPagination(SelectQuery $query, SortablePageableDto $dto)
	{
		if (!empty($dto->getPerPage())) {
			$query->limit($dto->getPerPage());
		}

		if (!empty($dto->getPage()) && !empty($dto->getPerPage())) {
			$query->offset($dto->getPerPage() * ($dto->getPage() - 1));
		}
	}

	public function applySortingAndPagination(SelectQuery $query, SortablePageableDto $dto)
	{
		$this->applySorting($query, $dto);
		$this->applyPagination($query, $dto);
	}
}
