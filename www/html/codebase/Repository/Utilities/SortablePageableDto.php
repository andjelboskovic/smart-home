<?php

namespace SmartHome\Repository\Utilities;


class SortablePageableDto implements \JsonSerializable
{
	private $page = 1;
	private $perPage = 10;
	private $orderBy = ['id' => 'asc'];

	private $total = 0;
	private $list = [];

	public function __construct($page, $perPage, $orderBy = ['id' => 'asc'])
	{
		$this->page = $page;
		$this->perPage = $perPage;
		$this->orderBy = $orderBy;
	}

	public function getPage()
	{
		return $this->page;
	}

	public function setPage($page)
	{
		$this->page = $page;
	}

	public function getPerPage()
	{
		return $this->perPage;
	}

	public function setPerPage($perPage)
	{
		$this->perPage = $perPage;
	}

	public function getOrderBy()
	{
		return $this->orderBy;
	}

	public function setOrderBy($orderBy)
	{
		$this->orderBy = $orderBy;
	}

	public function getTotal()
	{
		return $this->total;
	}

	public function setTotal($total)
	{
		$this->total = $total;
	}

	public function getList()
	{
		return $this->list;
	}

	public function setList($list)
	{
		$this->list = $list;
		return $this;
	}

	function jsonSerialize()
	{
		return [
			'page' => $this->page,
			'perPage' => $this->perPage,
			'total' => $this->total,
			'list' => $this->getList()
		];
	}
}
