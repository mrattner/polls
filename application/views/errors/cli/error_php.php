<?php
defined('BASEPATH') OR exit('No direct script access allowed');

echo "\nA PHP Error was encountered\n\n",

'Severity: ', $severity, "\n",
'Message: ', $message, "\n",
'Filename: ', $filepath,"\n",
'Line Number: ', $line, "\n\n";

if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE) {

	echo "Backtrace:\n\n";
	foreach (debug_backtrace() as $error) {
		if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0) {
			echo 'File: ', $error['file'], "\n",
			'Line: ', $error['line'], "\n",
			'Function: ', $error['function'],
			"\n\n";
		}
	}
}