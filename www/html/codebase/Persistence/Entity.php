<?php

namespace SmartHome\Persistence;

use SmartHome\Exception\PlatformException;
use JsonSerializable;
use ReflectionClass;

class Entity implements JsonSerializable
{
	const FIELD_TYPE_STRING = 'string';
	const FIELD_TYPE_INT = 'int';
	const FIELD_TYPE_FLOAT = 'float';
	const FIELD_TYPE_BOOL = 'bool';
	const FIELD_TYPE_ARRAY = 'array';
	const FIELD_TYPE_OBJECT = 'object';
	const FIELD_TYPE_DATE = 'date';
	const FIELD_TYPE_DATETIME = 'datetime';
	const FIELD_TYPE_TIMEZONE = 'timezone';

	private static $entityFields = [];
	private static $joinedTablesOnFields = [];

	/** @var int */
	protected $id;

	private $forceInsert = false;

	protected function __construct($dto = [])
	{
		$this->initializeFromDto($dto);
	}

	protected function initializeFromDto($dto = []): void
	{
		if (!empty($dto) && is_array($dto)) {
			foreach (self::getEntityFields() as $key => $data) {
				/** @var \ReflectionProperty $property */
				$property = $data['property'];
				$property->setAccessible(true);
				$property->setValue($this, $dto[$key] ?? null);
				$property->setAccessible(false);
			}
		}
	}

	public function getDto(): array
	{
		$dto = [];
		foreach (self::getEntityFields() as $key => $data) {
			/** @var \ReflectionProperty $property */
			$property = $data['property'];
			$property->setAccessible(true);
			$dto[$key] = $property->getValue($this);
			$property->setAccessible(false);
		}
		return $dto;
	}

	public function getParentDto(): array
	{
		$dto = [];
		if (get_parent_class($this)) {
			foreach (self::getEntityFields() as $key => $data) {
				/** @var \ReflectionProperty $property */
				$property = $data['property'];
				if ($property->class === get_parent_class($this)) {
					$property->setAccessible(true);
					$dto[$key] = $property->getValue($this);
					$property->setAccessible(false);
				}
			}
		}
		return $dto;
	}

	public function getChildDto(): array
	{
		$dto = [];
		foreach (self::getEntityFields() as $key => $data) {
			/** @var \ReflectionProperty $property */
			$property = $data['property'];
			if ($property->class !== get_parent_class($this)) {
				$property->setAccessible(true);
				$dto[$key] = $property->getValue($this);
				$property->setAccessible(false);
			}
		}
		return $dto;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function setId($id): void
	{
		$this->id = $id;
	}

	public function isForceInsert(): bool
	{
		return $this->forceInsert;
	}

	public function setForceInsert($forceInsert): void
	{
		$this->forceInsert = $forceInsert;
	}

	public function jsonSerialize(): array
	{
		return $this->getDto();
	}

	public static function getEntityFields(): array
	{
		$className = get_called_class();
		if (empty(self::$entityFields[$className])) {
			$reflection = new ReflectionClass($className);
			/** @var \ReflectionProperty $property */
			foreach ($reflection->getProperties() as $property) {
				$annotation = $property->getDocComment();
				if (strpos($annotation, "@field")) {
					self::$entityFields[$className][$property->getName()] = [
						'attributes' => self::_getFieldAnnotationAttributes($annotation),
						'property' => $property
					];
				}
			}
		}
		return self::$entityFields[$className];
	}

	private static function _getFieldAnnotationAttributes($annotation): array
	{
		$attributes = [];

		$regexp = "/@field\((((\s*([a-zA-Z_]+)\s*=\s*(([0-9a-zA-Z_]+))\s*)(,|))*)\)/";
		$matched = preg_match($regexp, $annotation, $matches);
		if ($matched === 1) {
			$regexp = "/(\s*([a-zA-Z_]+)\s*=\s*(([0-9a-zA-Z_]+))\s*)(,|)/";
			$attributeString = $matches[1];
			$matched = preg_match_all($regexp, $attributeString, $attributeMatches);
			if (!empty($matched)) {
				foreach ($attributeMatches[2] as $ind => $attribute) {
					$attributes[$attribute] = $attributeMatches[3][$ind];
				}
			}
		}

		return $attributes;
	}

	public static function getJoinedTablesAndFields(): array
	{
		$className = get_called_class();
		if (!isset(self::$joinedTablesOnFields[$className])) {
			self::$joinedTablesOnFields[$className] = [];
			$reflection = new ReflectionClass($className);
			/** @var \ReflectionProperty $property */
			foreach ($reflection->getProperties() as $property) {
				$annotation = $property->getDocComment();
				$matches = '';
				if (preg_match('/@join\s*\((.*)?\)/', $annotation, $matches) === 1) {
					$data = preg_split('/[\s=,]+/', $matches[1]);
					$attributes = [];
					while (count($data) > 1) {
						$key = trim(array_shift($data));
						$value = trim(array_shift($data));
						$attributes[$key] = $value;
					}

					if (empty($attributes['table'])) {
						throw new PlatformException("Annotation @join() does not have required attribute 'table' in {$className}.");
					}

					if (empty($attributes['field'])) {
						throw new PlatformException("Annotation @join() does not have required attribute 'field' in {$className}.");
					}

					self::$joinedTablesOnFields[$className][$attributes['table']] = $attributes;
				}
			}
		}
		return self::$joinedTablesOnFields[$className];
	}

	public static function deduplicate(array $entities): array
	{
		$deduplicated = [];
		/** @var Entity $entity */
		foreach ($entities as $entity) {
			$deduplicated[$entity->getId()] = $entity;
		}
		return array_values($deduplicated);
	}

	public static function arrayMerge(...$entityArrays): array
	{
		return self::deduplicate(array_merge(...$entityArrays));
	}

	public static function getIds(array $entities): array
	{
		return array_values(array_map(function (Entity $e) {
			return $e->getId();
		}, $entities));
	}
}
