<?php

	class Login {

		private $pdo, $return_text;

		public function __construct(){
			$this->pdo = DB::getInstance();
		}

		// beni hatirla
		public function auto_action( $user_id ){
            $Personel = new Personel( $user_id );
            if( $Personel->exists() ) {
                Active_User::init(array(
                    "id"        => $user_id,
                    "eposta"    => $Personel->get_details("eposta"),
                    "isim"      => $Personel->get_details("isim"),
                    "seviye"    => $Personel->get_details("seviye")
                ));
            }
		}

		// normal login
		public function action( $input ){
			// eposta kontrolu
            $Personel = new Personel( $input["eposta"] );
            if( $Personel->exists() ){
                $user_salt = $Personel->get_details("salt");
                $user_pass = $Personel->get_details("pass");
                $user_id   = $Personel->get_details("gid");
            } else {

                $this->pdo->insert( DBT_BASARISIZ_GIRISLER, array(
                    'eposta'    => $input['eposta'],
                    'ip'        => $_SERVER['REMOTE_ADDR'],
                    'mesaj'		=> 'Eposta yanlış',
                    'tarih'     => Common::get_current_datetime()
                ));

                $this->return_text = "Eposta veya şifre yanlış. Lütfen tekrar kontrol ediniz.";
                return false;
            }


			// sifre kontrolu
			$input_pass = hash( 'sha256', $user_salt . $input["pass"] );
			if( $input_pass != $user_pass ){
				$this->pdo->insert( DBT_BASARISIZ_GIRISLER, array(
					'eposta'    => $input['eposta'],
					'ip'        => $_SERVER['REMOTE_ADDR'],
					'mesaj'		=> 'Şifre yanlış',
					'tarih'     => Common::get_current_datetime()
				));
				$this->return_text = "Eposta veya şifre yanlış. Lütfen tekrar kontrol ediniz.";
				return false;
			}

			// remember me kontrolu
			if( isset( $input["remember_me"] ) ){
				$Auto_Login = new Auto_Login;
				$Auto_Login->update_remember_me_token($user_id);
			}

			Active_User::init( array(
				"id"              => $user_id,
				"eposta"          => $input["eposta"],
				"isim"            => $Personel->get_details("isim"),
                "seviye"          => $Personel->get_details("seviye")
			));

			Active_User::aktivite_kaydet( array( 'aktivite' => Aktivite::$PERS_GIRIS ) );

			$Personel->son_giris_guncelle();

			return true;
		}

		public function get_return_text(){
			return $this->return_text;
		}

	}