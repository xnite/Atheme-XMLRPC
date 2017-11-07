<?php

/**
 * Created by PhpStorm.
 * User: xnite
 * Date: 11/4/17
 * Time: 6:43 PM
 */
class ReplyObject
{
	private $status;
	private $string;
	private $message;
	public function __construct($reply, $status_code = 0)
	{
		$this->status = $status_code;
		$this->string = $reply;
		$this->message = $reply;
		return $this;
	}

	public function setStatusCode($code)
	{
		if(!is_numeric($code))
		{
			throw new exception("Status code must be numeric!");
			return false;
		}
		$this->status = $code;
		return true;
	}

	public function getStatusCode()
	{
		return $this->status;
	}

	public function getReply()
	{
		return $this->string;
	}

	public function getMessage()
	{
		if(is_string($this->message))
		{
			return $this->message;
		}
		return null;
	}
}