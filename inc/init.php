<?php

	// Error output log


	require 'defs.php';

    ini_set('error_log',  MAIN_DIR . "error.log");

	// Otomatik class include
	function autoload_main_classes($class_name){
		$file = CLASS_DIR . $class_name. '.php';
	    if (file_exists($file)) require_once($file);
	}
	spl_autoload_register( 'autoload_main_classes' );

	Session::start();

	$DBSETUP = new DBSetup();
	//$DBSETUP->tablolari_olustur();

	// perm kontrolleri yapilacak
	/*if( !Session::exists("login_session") ) {
		$Auto_Login = new Auto_Login;
		if( $Auto_Login->check() ){
			$Login = new Login;
			$Login->auto_action( $Auto_Login->get_user_id() );
		} else {
			if( !isset($LOGIN_PROCESS) ){
				header("Location: login.php" );
				exit;
			}
		}
	}*/