<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require('./assets/files/php/includes/ethereum.php');

if( ! function_exists('_balance'))
{
	function _balance($address)
	{
		$poa = new Ethereum('https://core-trace.poa.network', '');
		$balance = _wei_to_ether($poa->eth_getBalance($address, 'latest', TRUE));
		return $balance;

	}
}


if( ! function_exists('_wei_to_ether'))
{
	function _wei_to_ether($data)
	{
		return $data / pow(10,18);

	}
}