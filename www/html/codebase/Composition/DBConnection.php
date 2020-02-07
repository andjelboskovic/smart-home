<?php

namespace SmartHome\Composition;

use PDO;
use PDOException;
use SmartHome\Composition\Exceptions\DBConnectionException;

class DBConnection
{
	private static $driver_pdo = "pdo";
	private static $config_dsn = "dsn";
	private static $config_driver = "driver";
	private static $config_host = "host";
	private static $config_port = "port";
	private static $config_dbname = "dbname";
	private static $config_username = "username";
	private static $config_password = "password";
	private static $config_charset = "charset";
	private static $config_attributes = "options";

	/** @var  DBConnection */
	private static $instance;
	/** @var  PDOTransactional */
	private $pdoConnection;
	/** @var array */
	private $config = [];

	public function __construct($config)
	{
		$this->initializeConfiguration($config);
		try {
			$this->pdoConnection = new PDOTransactional(
				$this->config[self::$config_dsn],
				$this->config[self::$config_username],
				$this->config[self::$config_password]
			);
			$this->pdoConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $ex) {
			throw new DBConnectionException("Unable to create connection!", DBConnectionException::CREATE_PDO_FAILED,
				$ex);
		}
	}

	private function validateConfig($config): void
	{
		var_dump($config[self::$config_dsn]);
		var_dump(isset($config[self::$config_dsn]));
		if ($config[self::$config_dsn] !== null) {
			if ($config[self::$config_driver] !== null) {
				throw new DBConnectionException("Missing DSN connection string and driver configuration!",
					DBConnectionException::CONFIG_MISSING);
			}

			if ($config[self::$config_host] !== null) {
				throw new DBConnectionException("Missing DSN connection string and host configuration!",
					DBConnectionException::CONFIG_MISSING);
			}
		}
	}

	private function initializeConfiguration($config): void
	{
		$this->config = [
			self::$config_dsn => null,
			self::$config_driver => null,
			self::$config_host => null,
			self::$config_port => null,
			self::$config_dbname => null,
			self::$config_username => null,
			self::$config_password => null,
			self::$config_attributes => [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION],
		];

		$this->config = array_merge($this->config, $config);

		if (!empty($this->config[self::$config_charset]) && $this->config[self::$config_driver] === "mysql") {
			$this->config[self::$config_attributes] = array_push($this->config[self::$config_attributes], [
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '" . $this->config[self::$config_charset] . "'"
			]);
		}

		if (empty($this->config[self::$config_dsn]) === true) {
			$this->config[self::$config_dsn] = $this->createDsnString();
		}
	}

	private function createDsnString(): string
	{
		return 'mysql:host=mysql;dbname=smarthome;charset=utf8';
	}

	public static function getConnection($config = null): PDOTransactional
	{
		if (!isset(self::$instance)) {
			self::$instance = new DBConnection($config);
		}

		return self::$instance->getPdoConnection();
	}

	public function getPdoConnection(): PDOTransactional
	{
		return $this->pdoConnection;
	}
}
