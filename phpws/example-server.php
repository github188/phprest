<?php
require_once 'RESTPHP/RESTPHP.php';

class Example extends RESTPHP_Server {
	private $dbH;

	const TEMPLATE_DIR = "templates";

	public function __construct($authObj = NULL) {
		parent::__construct($authObj);
		$this->dbH = new PDO("mysql:host=10.10.10.29;dbname=test;", 'root', 'password');
	}

	public function insert() {
		$SQL = "INSERT INTO ws_test (name, phone) VALUES (:name, :phone);";
		$ins_st = $this->dbH->prepare($SQL);

		$params = array(
				":name" => $this->data['name'],
				":phone" => $this->data['phone'],
		);

		$res = $ins_st->execute($params);
		$id = $this->dbH->lastInsertId();
		$this->sendResponse($id);
	}

	public function update() {
		$SQL = "UPDATE ws_test SET name=:name, phone=:phone WHERE id=:id;";
		$upd_st = $this->dbH->prepare($SQL);

		$params = array(
				":name" => $this->data['name'],
				":phone" => $this->data['phone'],
				":id" => $this->id,
		);

		$res = $upd_st->execute($params);
		if($upd_st->rowCount() > 0) {
			$this->sendResponse($this->id);
		} else {
			$this->sendResponse("Nothing was affected", 404);
		}
	}

	public function delete() {
		$SQL = "DELETE FROM ws_test WHERE id=:id;";
		$del_st = $this->dbH->prepare($SQL);

		$params = array(
				":id" => $this->id,
		);

		$res = $del_st->execute($params);
		if($del_st->rowCount() > 0) {
			$this->sendResponse("TRUE");
		} else {
			$this->sendResponse("Nothing was affected", 404);
		}
	}

	public function read() {
		if(empty($this->id)) {
			$this->showDoc();
			return;
		}
		$SQL = "SELECT * FROM ws_test WHERE id=? LIMIT 1;";
		$read_st = $this->dbH->prepare($SQL);
		$read_st->execute(array($this->id));
		if($read_st->rowCount() > 0) {
			$info = $read_st->fetchAll(PDO::FETCH_OBJ);
			$info = json_encode($info);
			$this->sendResponse($info);
		} else {
			$this->sendResponse("Unknown item", 404);
		}
	}
}

try {
	$ex = new Example(new NoAuth());
	$ex->handleRequest();
} catch(RESTPHP_Server_Exception $e) {
	RESTPHP_Server::Respond($e->getMessage(), $e->getCode());
}
?>