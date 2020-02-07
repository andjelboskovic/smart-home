<?php


namespace Composition;


use SmartHome\Persistence\Entity;

class DatabaseToEntityMapping
{
	private static $dbTableToEntityMap = [
		'home' => 'SmartHome\\Persistance\\Home'
	];

	public static function getDbTableToEntityMap()
	{
		return self::$dbTableToEntityMap;
	}

	public static function getEntityForTable(string $table): ?string
	{
		if (isset(self::$dbTableToEntityMap[$table])) {
			return self::$dbTableToEntityMap[$table];
		}
		return null;
	}

	public static function getTableNameForEntity(Entity $entity): ?string
	{
		$tableName = array_search(get_class($entity), self::$dbTableToEntityMap);

		if ($tableName !== false) {
			return $tableName;
		} else {
			return null;
		}
	}
}
