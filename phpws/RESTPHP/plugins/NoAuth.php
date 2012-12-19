<?php
class NoAuth implements RESTPHP_Auth {
	function isValidRequester() {
		return TRUE;
	}
}
?>