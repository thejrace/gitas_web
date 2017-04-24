<?php

	class Logout {
		private $pdo;
		public function __construct(){
			$this->pdo = DB::getInstance();
			$this->user_id = Session::get("au_id");
            Active_User::aktivite_kaydet( array( 'aktivite' => Aktivite::$PERS_CIKIS ) );
		}

		public function action(){
			$this->destroy_sessions();
			$this->destroy_cookie();
		}

		private function destroy_sessions(){
			Session::destroy( "au_id" );
			Session::destroy( "au_isim" );
			Session::destroy( "au_eposta" );
			Session::destroy( "au_seviye" );
		}

		private function destroy_cookie(){
			Cookie::destroy("obarey_rm");
			if( ! $this->pdo->query("DELETE FROM ". DBT_COOKIE_TOKENS . " WHERE personel = ?", array($this->user_id)) ) return false;
		}
	}