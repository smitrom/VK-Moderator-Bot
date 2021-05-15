<?php 

function StrContains($where, $what) {
	$bool = mb_stripos($where, $what) !== false ? true : false;
	return $bool;
}

?>