<?php

/**
 * Created by PhpStorm.
 * User: xnite
 * Date: 10/30/17
 * Time: 2:04 PM
 */
class Chanserv
{
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

	public function register($channel)
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"ChanServ","register", $channel));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		return $response;
	}

	public function akickAdd($channel, $user_mask, $time, $reason)
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"ChanServ","akick", $channel, "add", $user_mask, $time, $reason));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		return $response;
	}

	public function akickDel($channel, $user_mask)
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"ChanServ","akick", $channel, "del", $user_mask));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		return $response;
	}

	public function info($channel)
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"ChanServ","info", $channel));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = explode("\n", xmlrpc_decode($file));
		$return = array();
		foreach($response as $line)
		{
			if(preg_match("/^Founder([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['founder']=$matches[3]; }
			if(preg_match("/^Successor([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['successor']=$matches[3]; }
			if(preg_match("/^Registered([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['registered']=$matches[3]; }
			if(preg_match("/^Mode lock([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['mlock']=$matches[3]; }
			if(preg_match("/^Entrymsg([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['entrymsg']=$matches[3]; }
			if(preg_match("/^Flags([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['flags']=$matches[3]; }
			if(preg_match("/^Prefix([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['prefix']=$matches[3]; }
			if(preg_match("/^AntiFlood([\s]+):([\s]+)(.*?)$/i", $line, $matches)) { $return['antiflood']=$matches[3]; }
		}
		return new ReplyObject($return, 200, "CS_FOUND_CHANINFO");
	}
	public function akickList($channel)
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"ChanServ","akick", "list", $channel));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = explode("\n", xmlrpc_decode($file));
		$return = array();
		foreach($response as $line)
		{
			if(preg_match("/^([0-9]+): (.*?) \((.*?)\) \[setter: (.*?), expires: (.*?), modified: (.*?)\]$/i", $line, $m)) {
				array_push($return, array(
					'id'=>$m[1],
					'hostmask'=>$m[2],
					'reason'=>$m[3],
					'setter'=>$m[4],
					'expire'=>$m[5],
					'modified'=>$m[6]
				));
			}
		}
		return $return;
	}
}