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

function is_base64($char) {
	$charint = ord($char);
	if (($charint >= 65 && $charint <= 90) ||
	    ($charint >= 97 && $charint <= 122) ||
	    ($charint >= 48 && $charint <= 57) ||
	    $charint == 43 || $charint == 47 || $charint == 61) {
	    	return True;
	} 
	return False;
}

function reformat_pgp_message($message) {
	if (!str_contains($message, "\n")) $message = $message . "\n";
	$msg_type = "";

	$is_pgp = False;

	if (str_contains($message, "-----BEGIN PGP MESSAGE-----")) {
		$msg_type = "message";
		$is_pgp = True;
	}

	if (str_contains($message, "-----BEGIN PGP PUBLIC KEY BLOCK-----")) {
		$msg_type = "pubkey";
		$is_pgp = True;
	}

	$message = str_replace("-----BEGIN PGP MESSAGE-----", "", $message);
	$message = str_replace("-----BEGIN PGP PUBLIC KEY BLOCK-----", "", $message);
	$message = str_replace("-----END PGP MESSAGE-----", "", $message);
	$message = str_replace("-----END PGP PUBLIC KEY BLOCK-----", "", $message);

	$chars = str_split($message);
	$message = "";
	$increment = 0;

	foreach ($chars as $char) {
		if (is_base64($char)) {
		    	$message = $message . $char;
			$increment++;
		}

		if ($increment == 64) {
			$increment = 0;
			$message = $message . "\n";
		}
	}

	$theend = substr($message, strlen($message) - 5, strlen($message));
	$message = substr($message, 0, strlen($message) - 5);

	if ($is_pgp === True) {
		$return_string = "";

		if ($msg_type === "message") {
			$return_string = $return_string . "-----BEGIN PGP MESSAGE-----\n";
		}

		if ($msg_type === "pubkey") {
			$return_string = $return_string . "-----BEGIN PGP PUBLIC KEY BLOCK-----\n";
		}

		$return_string = $return_string . $message . "\n" . $theend . "\n";

		if ($msg_type === "message") {
			$return_string = $return_string . "-----END PGP MESSAGE-----\n";
		}

		if ($msg_type === "pubkey") {
			$return_string = $return_string . "-----END PGP PUBLIC KEY BLOCK-----\n";
		}
	} else {
		$return_string = "Message is not valid GPG!\n";
	}

	return $return_string;
}
