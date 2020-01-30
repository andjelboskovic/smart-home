<?php


use SmartHome\Persistence\Home;

class HomeRepository extends AbstractRepository
{
	/** @var self */
	protected static $instance;

	const TABLE_NAME = 'home';
	const ENTITY_CLASS = Home::class;

}
