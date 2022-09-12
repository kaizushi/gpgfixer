<?php

/* gpgfixer php version!
 *
 * This is the PHP version of gpgfixer which was originally written in Python.
 * It is to be used to patch the Wordpress plugin flexible checkout fields. The
 * python version was created so that my customers can fix GPG messages with any
 * kind of formatting issue. This PHP version was created so that reformatting is
 * built into customers sites saving them time.
 *
 * This code is intended to be called from the function printCheckoutFields in the
 * file classes/plugin.php of the Flexible Checkout Fields extension for Wordpress
 * by WPDesk. 
 */

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

function reformat_pgp_message($message) {
	$lines = explode("\n", $message);
	$msg_type = "";

	$is_pgp = False;
	$output_lines = array();
	foreach ($lines as $line) {
		//$line = $line[0];
		if (str_contains($line, "-----BEGIN PGP MESSAGE-----")) {
			$msg_type = "message";
			$is_pgp = True;
			continue;
		}
		if (str_contains($line, "-----BEGIN PGP PUBLIC KEY BLOCK-----")) {
			$msg_type = "pubkey";
			$is_php = True;
			continue;
		}
		if (str_contains($line, "-----END PGP MESSAGE-----")) {
			continue;
		}
		if (str_contains($line, "-----END PGP PUBLIC KEY BLOCK-----")) {
			continue;
		}

		$line = mb_convert_encoding($line, "ASCII");
		$chars = str_split($line);
		
		$i = 0;
		$new_line = "";

		$finish_line = False;
		foreach ($chars as $char) {
			$charint = ord($char);
			if (($charint >= 65 && $charint <= 90) || 
			    ($charint >= 97 && $charint <= 122) ||
			    ($charint >= 48 && $charint <= 57) ||
			    $charint == 43 || $charint == 47 || $charint == 61) {
			    	$new_line = $new_line . $char;
				$i + $i + 1;
			}
			if ($i == 64) break;
		}

		$new_line = $new_line . "\n";
		if ($output_lines !== "") array_push($output_lines, mb_convert_encoding($new_line, "UTF-8"));
	}

	$return_string = "";

	if ($msg_type === "message") {
		$return_string = $return_string . "-----BEGIN PGP MESSAGE-----\n";
	}

	if ($msg_type === "pubkey") {
		$return_string = $return_string . "-----BEGIN PGP PUBLIC KEY BLOCK-----\n";
	}

	foreach ($output_lines as $line) {
		$return_string = $return_string . $line;
	}

	if ($msg_type === "message") {
		$return_string = $return_string . "-----END PGP MESSAGE-----\n";
	}

	if ($msg_type === "pubkey") {
		$return_string = $return_string . "-----END PGP PUBLIC KEY BLOCK-----\n";
	}

	return $return_string;
}
