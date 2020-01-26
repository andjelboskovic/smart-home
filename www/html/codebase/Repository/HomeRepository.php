<?php


namespace Repository;


use SmartHome\Persistence\Home;
use SmartHome\Repository\AbstractRepository;

class HomeRepository extends AbstractRepository
{
	/** @var self */
	protected static $instance;

	const TABLE_NAME = 'home';
	const ENTITY_CLASS = Home::class;

}
