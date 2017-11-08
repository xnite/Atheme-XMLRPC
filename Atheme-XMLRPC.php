<?php

/**
 * Created by PhpStorm.
 * User: xnite
 * Date: 10/28/17
 * Time: 4:48 PM
 */

require_once(dirname(__FILE__) . "/lib/Nickserv.php");
require_once(dirname(__FILE__) . "/lib/Chanserv.php");
require_once(dirname(__FILE__) . "/lib/Memoserv.php");
require_once(dirname(__FILE__) . "/lib/Hostserv.php");
require_once(dirname(__FILE__) . "/lib/ReplyObject.php");

class Atheme
{
	public $xmlrpc_url;
	public $authToken;
	public $source_ip;
	public $username;

	public $nickserv;
	public $chanserv;
	public $memoserv;
	public $hostserv;

	public function __construct($xmlrpc_path = null)
	{
		if(isset($_SERVER['REMOTE_ADDR']))
		{
			$this->source_ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$this->source_ip = "127.0.0.1";
		}
		if($xmlrpc_path == null)
		{
			throw new exception("xmlrpc path is a required parameter");
		}
		$this->xmlrpc_url = $xmlrpc_path;
		$this->nickserv = new Nickserv($this->xmlrpc_url);
	}

	public function auth($username, $password)
	{
		$Auth = $this->nickserv->identify($username,$password);
		if($Auth->getStatusCode() == 0)
		{
			$this->authToken=$Auth->getReply();
			$this->username=$this->nickserv->username;
			$this->chanserv = new Chanserv($this->xmlrpc_url);
			$this->chanserv->authToken = $Auth->getReply();
			$this->chanserv->username = $username;
			$this->hostserv = new Hostserv($this->xmlrpc_url);
			$this->hostserv->authToken = $Auth->getReply();
			$this->hostserv->username = $username;
			$this->memoserv = new Memoserv($this->xmlrpc_url);
			$this->memoserv->authToken = $Auth->getReply();
			$this->memoserv->username = $username;
			return new ReplyObject($this->authToken, 0, "NS_IDENTIFY_OK");
		} else {
			return new ReplyObject($Auth->getReply(), $Auth['faultCode']);
		}
		return new ReplyObject("An unknown error occurred!", 9);
	}
}