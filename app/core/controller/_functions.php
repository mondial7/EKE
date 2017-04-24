<?php

/**
 * The Lorem Ipsum
 */
function bar(){

	return 'foo';

}

/**
 * Check if multiple inputs are set
 *
 * @param array string inputs
 * @return boolean
 *
 */
function areset($keys){

	foreach ($keys as $key) {
	   
	    if (!isset($_REQUEST[$key]) || empty($_REQUEST[$key])) {
	    	
	    	return false;

	    }
	
	}

	return true;

}