<?php

namespace SmartHome\Repository;

use CI_Controller;
use Exception;
use PDO;
use PDOException;
use SmartHome\Persistence\Entity;

abstract class AbstractRepository implements RepositoryInterface
{
	const TABLE_NAME = null;
	const ENTITY_CLASS = null;
	protected static $instance = null;
	/** @var object  */
	protected $ci;

	public function __construct()
	{
		$this->ci = CI_Controller::get_instance();
		$this->ci->load->database();
	}

	/**
	 * @return static
	 */
	public static function getInstance(): self
	{
		if (empty(static::$instance)) {
			static::$instance = new static();
		}
		return static::$instance;
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
		//TODO Fix this to work with $this->db

//	$this->db->select('*')
//		->from(self::getTableName())
//		->where('device_id', $device['id'])
//		->order_by('date_created', 'DESC')
//		->limit()
//		->get();
//		$dto = $this->toDatabaseDto($this->getEntityClass(), $entity->getDto());
//		unset($dto['id']);
//
//		$query = null;
//		if (intval($entity->getId()) > 0 && !$entity->isForceInsert()) {
//			$query = $this->queryBuilder->update($this->getTableName(), $dto, $entity->getId());
//		} else {
//			$query = $this->queryBuilder->insertInto($this->getTableName(), $dto);
//		}
//		try {
//			$result = $query->execute();
//		} catch (PDOException $ex) {
//			throw new Exception($ex, $query);
//		}
//
//		// Update object with ID of new row in DB
//		if ($updateId && !(intval($entity->getId()) > 0 && !$entity->isForceInsert())) {
//			$entity->setId($result);
//		}
//
//		return $result;
		return [];
	}

	public function getById($id)
	{
		return $this->findOneBy(['id' => intval($id)]);
	}

	public function findOneBy($criteria = null)
	{
		$results = $this->findBy($criteria);
		return array_shift($results);
	}

	public function findBy($criteria = null)
	{
		$query = $this->db->select('*')
			->from(self::getTableName())
			->where($criteria)
			->get();

		return $query->result_array();
	}

	public function getByIds(array $ids): array
	{
		if (empty($ids)) {
			return [];
		}
		return $this->findBy(['id' => $ids]);
	}
}
