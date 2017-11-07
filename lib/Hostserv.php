<?php

/**
 * Created by PhpStorm.
 * User: xnite
 * Date: 10/30/17
 * Time: 1:59 PM
 */
class Hostserv
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

	public function request($hostname = null)
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

	public function take($vhost = null)
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

	public function offerList()
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"HostServ","offerlist"));
		$context = stream_context_create(array('http' => array('method' => "POST", 'header' => "Content-Type: text/xml", 'content' => $request)));
		$file = file_get_contents($this->xmlrpc_url, false, $context);
		$response = xmlrpc_decode($file);
		return $response;
	}
}