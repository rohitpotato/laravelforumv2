<?php

namespace App;

use Illuminate\Support\Facades\Redis;

trait RecordsVisits
{
	public function visits()
	{
		return Redis::get("threads.{$this->id}.visits");
	}

	public function recordVisits()
	{
		Redis::incr("threads.{$this->id}.visits");

		return $this;
	}
}