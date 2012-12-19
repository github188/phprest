<?php
class RESTPHP_Client {
	private $method;
	private $url;
	private $curlH;
	private $data;
	private $response;
	private $status_code;

	CONST READ   = CURLOPT_HTTPGET;
	CONST INSERT = CURLOPT_POST;
	CONST UPDATE = CURLOPT_CUSTOMREQUEST;
	CONST DELETE = CURLOPT_CUSTOMREQUEST;

	public function __construct($url, $method = NULL, $options = NULL) {
		$this->url = $url;

		$this->openConnection($this->url);
		curl_setopt($this->curlH, CURLOPT_RETURNTRANSFER, TRUE);

		if(!empty($method)) {
			$this->setMethod($method);
		}
	}

	public function setMethod($method = 'READ') {
		$this->method = $method;

		if($method != "DELETE" && $method != "UPDATE") {
			$tmp = new ReflectionObject($this);
			curl_setopt($this->curlH, $tmp->getConstant($method), TRUE);
			unset($tmp);
		} elseif($method == "UPDATE") {
			curl_setopt($this->curlH, self::DELETE, "PUT");
		} else {
			curl_setopt($this->curlH, self::DELETE, "DELETE");
		}
	}

	public function setRequestData($data) {
		$this->data = http_build_query($data);

		if($this->method != "READ") {
			curl_setopt($this->curlH, CURLOPT_POSTFIELDS, $this->data);
		}
	}

	public function setId($id) {
		$s = (strpos($this->url, "?") > 0)?"&":"?";
		$newUrl = $this->url.$s."id=".$id;
		curl_setopt($this->curlH, CURLOPT_URL, $newUrl);
	}

	public function sendRequest() {
		$this->response = curl_exec($this->curlH);
		$this->status_code = curl_getinfo($this->curlH, CURLINFO_HTTP_CODE);
		$err = curl_error($this->curlH);
		if(!empty($err)) {
			throw new RESTPHP_Client_Exception($err);
		}
	}

	public function getResponse() {
		return $this->response;
	}

	public function getStatusCode() {
		//print_r(curl_getinfo($this->curlH));
		return $this->status_code;
	}

	private function openConnection($url) {
		$this->curlH = curl_init($url);
	}

	private function closeConnection() {
		curl_close($this->curlH);
	}
}
?>