<!-- https://github.com/HlaingTinHtun/php_pdo -->
<!-- generate own csrf token and store in session for csrf -->
<?php

// session_start();

if (empty($_SESSION['_token'])) {
	if (function_exists('random_bytes')) {
		$_SESSION['_token'] = bin2hex(random_bytes(32));
	} else if (function_exists('mcrypt_create_iv')) {
		$_SESSION['_token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
	} else {
		$_SESSION['_token'] = bin2hex(openssl_random_pseudo_bytes(32));
	}
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!hash_equals($_SESSION['_token'], $_POST['_token'])){
		echo "Invalid CSRF token";
		die();
	}else{// form submit လုပ်ပြီးတိုင်း token ပြောင်းနေ
		unset($_SESSION['_token']);
	}
}

/**
 * Escapes HTML for output
 *
 */
// for xss attack protection
function escape($html) {
	return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}