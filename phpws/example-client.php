<?php
require_once 'RESTPHP/RESTPHP.php';

$url = sprintf("%s%s", $_SERVER['HTTP_HOST'], dirname($_SERVER['PHP_SELF']) . "/example-server.php");

$action = "INSERT";
$action = "DELETE";
$action = "UPDATE";
$action = "READ";

class ExampleClient extends RESTPHP_Client {}

$exc = new ExampleClient($url);

switch($action) {
	case 'INSERT':
		/**
		 * INSERT DEMO
		*/
		$exc->setMethod("INSERT");
		$data = array("name" => "RoS", "phone" => "123");
		$exc->setRequestData($data);
		break;

	case 'READ':
		/**
		 * READ DEMO
		 */
		$exc->setMethod("READ");
		$exc->setId(1);
		break;

	case 'UPDATE':
		/**
		 * UPDATE DEMO
		 */
		$exc->setMethod("UPDATE");
		$data = array("name" => "rantsh", "phone" => "654");
		$exc->setId(1);
		$exc->setRequestData($data);
		break;

	case 'DELETE':
		/**
		 * DELETE DEMO
		 */
		$exc->setMethod("DELETE");
		$exc->setId(13);
		break;

	default:
		break;
}

$exc->sendRequest();
echo $exc->getStatusCode();
echo $exc->getResponse();
?>