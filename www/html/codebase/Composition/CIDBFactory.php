<?php

namespace SmartHome\Composition;

use SmartHome\Composition\DBConnection;

class CIDBFactory
{
	const MODE_DEFAULT = 'default';
	const MODE_READONLY = 'readonly';

	const CHARSET_UTF8 = 'utf8';
	const CHARSET_UTF8MB4 = 'utf8mb4';


	public static function getConnection()
	{
		$config = self::initializeConfig(self::MODE_DEFAULT);
		return DBConnection::getConnection($config);
	}

	public static function getReadonlyConnection()
	{
		$config = self::initializeConfig(self::MODE_READONLY);
		return new DBConnection($config);
	}

	private static function initializeConfig(string $mode)
	{
		// Path to config files
		$envSpecific = defined('ENVIRONMENT') ? APPPATH . 'config/' . ENVIRONMENT . '/database.php' : null;
		$default = APPPATH . 'config/database.php';
		$configPath = file_exists($envSpecific) ? $envSpecific : $default;
		if (!file_exists($configPath)) {
			throw new \Exception("Database configuration path does not exists!");
		}

		// This will include $db var;
		include($configPath);

		$active_group = $mode;
		if (!isset($db) || !isset($active_group) || !isset($db[$active_group])) {
			throw new \Exception("Invalid configuration found in {$configPath}");
		}

		$ciConfig = $db[$active_group];
		return [
			'driver' => $ciConfig['dbdriver'] === 'mysqli' ? 'mysql' : $ciConfig['dbdriver'],
			'host' => $ciConfig['hostname'],
			'dbname' => $ciConfig['database'],
			'username' => $ciConfig['username'],
			'password' => $ciConfig['password'],
			'charset' => self::CHARSET_UTF8MB4
		];
	}
}
