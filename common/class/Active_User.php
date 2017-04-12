<?php

	class Active_User {

		public static function init( $data ){
			foreach( $data as $key => $value ){
			    Session::set( "au_".$key, $value );
            }
		}

		public static function get_details($key = null){
			return Session::get("au_".$key);
		}


		public static function aktivite_kaydet( $data ){

			DB::getInstance()->insert(DBT_AKTIVITE_KAYIT, array(
				'personel' => self::get_details("id"),
				'aktivite' => $data["aktivite"],
				'tarih'	   => Common::get_current_datetime()
			));

		}

	}