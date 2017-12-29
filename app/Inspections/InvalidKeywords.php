<?php

namespace App\Inspections;
use Exception;

class InvalidKeywords
{

	protected $invalidKeywords = ['yahoo customer support'];

	public function detect($body)
	{

		foreach($this->invalidKeywords as $invalids) {
			
			if(stripos($body, $invalids) !== false)
			{
				throw new Exception("Your reply contains spam");

			}

		}
	}

}