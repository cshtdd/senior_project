<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('getCurrentUserId'))
{
	function getCurrentUserId($sender_controller)
	{
		$is_test = true;
		if ($is_test)
		{
			return 101;
		}
		else
		{
			throw new Exception('not implemented');
		}
	}
}

if ( !function_exists('isUserLoggedIn'))
{
	function isUserLoggedIn($sender_controller)
	{
		$is_test = true;
		if ($is_test)
		{
			return true;
		}
		else
		{
			throw new Exception('not implemented');
		}
	}
}

?>