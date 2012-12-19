<?php
interface RESTPHP_Auth {
	/**
	 * Determines if the requester is a valid one
	 *
	 * @return boolean
	 */
	function isValidRequester();
}
?>