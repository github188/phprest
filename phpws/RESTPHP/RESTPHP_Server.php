<?php
require_once 'RESTPHP_Exceptions.php';

class RESTPHP_Server {
	protected $rMethod;
	protected $id;
	protected $data;

	const TEMPLATE_DIR = "";

	public function __construct() {
		$this->rMethod = $this->determineRequest();
		if($this->rMethod == "PUT") {
			parse_str(file_get_contents("php://input"), $data);
		} else {
			$data = $_REQUEST;
		}
		$this->setData($data);
		$this->setId();
	}

	protected function read() {
		if(empty($this->id)) {
			$this->showDoc();
			return;
		}

		$this->notImplemented(__FUNCTION__);
	}

	protected function insert() {
		$this->notImplemented(__FUNCTION__);
	}

	protected function update() {
		$this->notImplemented(__FUNCTION__);
	}

	protected function deletes() {
		$this->notImplemented(__FUNCTION__);
	}

	protected function notImplemented($function) {
		throw new RESTPHP_Server_Exception(sprintf("%s has not been implemented", $function));
	}

	public function handleRequest() {
		switch($this->rMethod) {
			case 'GET':
				$this->read();
				break;
			case 'POST':
				$this->insert();
				break;
			case 'PUT':
				$this->update();
				break;
			case 'DELETE':
				$this->delete();
				break;
		}
	}

	protected function determineRequest() {
		//printf("Request Method: %s", $_SERVER['REQUEST_METHOD']);
		return $_SERVER['REQUEST_METHOD'];
	}

	protected function setId() {
		if(!empty($_GET['id'])) {
			$this->id = intval($_GET['id']);
		} else {
			$this->id = NULL;
		}
	}

	protected function showDoc() {
		$c = get_class($this);
		$filename = $c::TEMPLATE_DIR . "/" . basename($_SERVER['PHP_SELF'], ".php") . ".html";
		if(!file_exists($filename)) {
			printf("There is currently no documentation for Web Service <b>%s</b>", get_class($this));
			return;
		}

		echo file_get_contents($filename);
	}

	protected function setData($data) {
		if(empty($data)) {
			$this->data = NULL;
			return;
		}
		$this->data = $data;
	}


	public function sendResponse($response, $code = 200) {
		if($code != 200) {
			$h = sprintf("HTTP/1.1 %d %s", $code, $response);
			header($h, TRUE, $code);
		} else {
			printf("%s%s", $response, PHP_EOL);
		}
		die();
	}
}
?>