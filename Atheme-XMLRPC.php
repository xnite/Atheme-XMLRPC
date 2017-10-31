<?php

/**
 * Created by PhpStorm.
 * User: xnite
 * Date: 10/28/17
 * Time: 4:48 PM
 */

require_once(dirname(__FILE__)."/services/Nickserv.php");
require_once(dirname(__FILE__)."/services/Chanserv.php");
require_once(dirname(__FILE__)."/services/Memoserv.php");
require_once(dirname(__FILE__)."/services/Hostserv.php");

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
}