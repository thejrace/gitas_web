<?php
	class Auto_Login {

		private $pdo, $return_text, $user_id;

		public function __construct(){
			$this->pdo = DB::getInstance();
		}

		public function check(){

			if( !Cookie::exists("obarey_rm") ) return false;
			$cookie = Cookie::get("obarey_rm");
			$selector  = substr($cookie, 0, 12 );
			$validator = substr($cookie, 12 );
			// selector kontrolu
			$selector_query = $this->pdo->query("SELECT * FROM " . DBT_COOKIE_TOKENS . " WHERE selector = ? ", array($selector) )->results();
			if( count($selector_query) != 1 ) {
				$this->return_text = "Selector DB de yok yalanji var gene.";
				return false;
			}
			// cookie deki validator den token olustur, dbki ile karsilastir
			$cookie_token = hash( 'sha256', $validator );
			$auth_token = $selector_query[0]["token"];


			if( !Common::hash_equals( $auth_token, $cookie_token) ){
				$this->return_text = "Tokenlar uyusmuyor. Yalanji var.";
				return false;
			}
            $this->user_id = $selector_query[0]["personel"];
			if( !$this->update_remember_me_token( $selector_query[0]["personel"] ) ) return false;
			return true;

		}

		public function update_remember_me_token( $user_id ){

			$selector  = substr( base64_encode( mcrypt_create_iv( 12, MCRYPT_DEV_URANDOM ) ), 0, 12 );
			$validator = base64_encode( mcrypt_create_iv( 32, MCRYPT_DEV_URANDOM ) );
			$token     = hash( 'sha256', $validator );

			// yeni mi ekleyecegiz yoksa update mi kontrol
			$exists_query = $this->pdo->query("SELECT * FROM " . DBT_COOKIE_TOKENS . " WHERE personel = ?", array($user_id))->results();
			if( count($exists_query) == 1 ){

				if( !$this->pdo->query("UPDATE " . DBT_COOKIE_TOKENS . " SET token = ?, selector = ? WHERE personel = ?", array(
                    "token"     => $token,
                    "selector"  => $selector,
                    "personel"  => $user_id
                )) ){
                    $this->return_text = "Yeni token update edilemedi.";
                    return false;
                }

			} else {	
				if( !$this->pdo->insert(DBT_COOKIE_TOKENS, array(
					"personel"  => $user_id,
					"selector"  => $selector,
					"token"     => $token
				))){ 
					$this->return_text = "Yeni token kayit edilemedi";
					return false;
				}
			}
			Cookie::setwithtime("obarey_rm", $selector.$validator, time()+86400*365 );
			return true;
		}

        public function get_user_id(){
            return $this->user_id;
        }

		public function get_return_text(){
			return $this->return_text;
		}

	}