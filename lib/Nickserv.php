<?php

/**
 * Created by PhpStorm.
 * User: xnite
 * Date: 10/30/17
 * Time: 1:54 PM
 */
class Nickserv
{
	public $services = array();
	public function __construct($xmlrpc_path)
	{
		$this->xmlrpc_url = $xmlrpc_path;
		if(isset($_SERVER['REMOTE_ADDR']))
		{
			$this->source_ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$this->source_ip = "127.0.0.1";
		}
	}

	public function identify($username = null,$password = null)
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
		if(is_array($response))
		{
			switch($response['faultCode'])
			{
				default:
					return new ReplyObject($response['faultString'], $response['faultCode']);
			}
		}
		return new ReplyObject($response, 0);
	}

	public function logout()
	{
		$request = xmlrpc_encode_request("atheme.logout", array($this->authToken, $this->username));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		if(is_array($response))
		{
			switch($response['faultCode'])
			{
				default:
					return new ReplyObject($response['faultString'], $response['faultCode']);
			}
		}
		return new ReplyObject($response, 0);
	}

	public function register($nickname, $email, $password)
	{
		$request = xmlrpc_encode_request("atheme.command", array("*", "*",$this->source_ip,"nickserv", "REGISTER", "Nickname", $password, $email));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		if(is_array($response))
		{
			switch($response['faultCode'])
			{
				default:
					return new ReplyObject($response['faultString'], $response['faultCode']);
			}
		}
		return new ReplyObject($response, 0);
	}

	public function set($key, $value)
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"NickServ","set", $key, $value));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		if(is_array($response))
		{
			switch($response['faultCode'])
			{
				default:
					return new ReplyObject($response['faultString'], $response['faultCode']);
			}
		}
		return new ReplyObject($response, 0);
	}

	public function setMeta($key, $value)
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"NickServ","set", "property", $key, $value));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		if(is_array($response))
		{
			switch($response['faultCode'])
			{
				default:
					return new ReplyObject($response['faultString'], $response['faultCode']);
			}
		}
		return new ReplyObject($response, 0);
	}

	public function info($account)
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"NickServ","info", $account));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		if(is_array($response))
		{
			switch($response['faultCode'])
			{
				default:
					return new ReplyObject($response['faultString'], $response['faultCode']);
			}
		}
		$response = explode("\n", $response);
		$return = array();
		foreach($response as $line)
		{
			if(preg_match("/^Registered([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['registration_time']=$matches[3]; }
			if(preg_match("/^vHost([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['vhost']=$matches[3]; }
			if(preg_match("/^Last Seen([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['last_seen']=$matches[3]; }
			if(preg_match("/^User seen([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['user_seen']=$matches[3]; }
			if(preg_match("/^Nicks([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['nicks']=$matches[3]; }
			if(preg_match("/^Email([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['email']=$matches[3]; }
			if(preg_match("/^Flags([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['flags']=$matches[3]; }
			if(preg_match("/^Language([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['language']=$matches[3]; }
			if(preg_match("/^Channels([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['channels']=$matches[3]; }
			if(preg_match("/^Metadata([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['metadata']=$matches[3]; }
		}
		return new ReplyObject($return, 0);
	}
	public function certList()
	{
		/*
		 * PLACEHOLDER!!
		 * Fingerprint list for XXXX:
		 * - XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
		 * - XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
		 * End of XXXX fingerprint list.
		 */
	}
}