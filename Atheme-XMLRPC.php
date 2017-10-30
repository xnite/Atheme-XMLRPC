<?php

/**
 * Created by PhpStorm.
 * User: xnite
 * Date: 10/28/17
 * Time: 4:48 PM
 */

class Atheme
{
	private $xmlrpc_url;
	public $authToken;
	public $source_ip;
	public $username;
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
	}

	/*
	 * NICKSERV METHODS
	 */
	public function nsIdentify($username = null,$password = null)
	{
		if($password == null)
		{
			throw new exception("Login method requires a username AND password. One or more of these were not provided.");
		}
		$request = xmlrpc_encode_request("atheme.login", array($username,$password));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		$this->username = $username;
		$this->authToken=$response;
		return $response;
	}

	public function nsLogout()
	{
		$request = xmlrpc_encode_request("atheme.logout", array($this->authToken, $this->username));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		return $response;
	}

	public function nsSet($key, $value)
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"NickServ","set", $key, $value));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		return $response;
	}

	public function nsSetMeta($key, $value)
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"NickServ","set", "property", $key, $value));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		return $response;
	}

	public function nsInfo($account)
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"NickServ","info", $account));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = explode("\n", xmlrpc_decode($file));
		$return = array();
		foreach($response as $line)
		{
			if(preg_match("/^Registered([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['registration_time']=$matches[3]; }
			if(preg_match("/^Last Seen([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['last_seen']=$matches[3]; }
			if(preg_match("/^Email([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['email']=$matches[3]; }
			if(preg_match("/^Metadata([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['metadata']=$matches[3]; }
		}
		return $return;
	}

	/*
	 * HOSTSERV METHODS
	 */

	public function hsRequest($hostname = null)
	{
		if($hostname == null)
		{
			throw new exception("Missing one or more required fields.");
		}
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"HostServ","request", $hostname));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		return $response;
	}

	public function hsTake($vhost = null)
	{
		if($vhost == null)
		{
			throw new exception("Missing one or more required fields.");
		}
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"HostServ","take", $vhost));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		return $response;
	}

	public function hsOfferList()
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"HostServ","offerlist"));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		return $response;
	}

	/*
	 * MEMOSERV METHODS
	 */
	public function msSend($to = null, $message = null)
	{
		if($message == null)
		{
			throw new exception("Missing one or more required variables!");
		}
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"MemoServ","send", $to, $message));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		return $response;
	}

	public function msList()
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"MemoServ","list"));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		return $response;
	}

	/*
	 * CHANSERV METHODS
	 */
	public function csRegister($channel = null)
	{
		if($channel == null)
		{
			throw new exception("Missing one or more required fields.");
		}
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"ChanServ","register", $channel));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		return $response;
	}
}
