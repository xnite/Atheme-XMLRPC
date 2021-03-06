<?php

/**
 * Created by PhpStorm.
 * User: xnite
 * Date: 10/30/17
 * Time: 2:02 PM
 */
class Memoserv
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

	public function send($to, $message)
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"MemoServ","send", $to, $message));
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

	public function list()
	{
		$request = xmlrpc_encode_request("atheme.command", array($this->authToken,$this->username,$this->source_ip,"MemoServ","list"));
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
}