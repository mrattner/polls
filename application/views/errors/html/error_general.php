<?php
defined('BASEPATH') OR exit('No direct script access allowed');

echo "\nERROR: ",
	$heading,
	"\n",
	str_replace('</p>', "\n", str_replace('<p>', "\n", $message)),
	"\n";