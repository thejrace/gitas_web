<?php

	define("DB_NAME", "hederoy");
	define("DB_PASS", "Dogansaringulu9");
	define("DB_IP", "127.0.0.1");

	define("APP_VERSION", "v0.1");

	// define("MAIN_DIR", "/home/ahsaphobby.net/httpdocs/granit/");
	// define("MAIN_DIR", realpath(dirname(__FILE__)));
	define("MAIN_DIR", $_SERVER["DOCUMENT_ROOT"] . "/gitasWeb/");

	define("COMMON_DIR", MAIN_DIR . "common/");
	define("CLASS_DIR", COMMON_DIR . "class/");


	define("DBT_PARCA_TIPLERI", "parca_tipleri");
	define("DBT_BARKODLU_PARCALAR", "barkodlu_parcalar");
	define("DBT_BARKODSUZ_PARCALAR", "barkodsuz_parcalar");
	define("DBT_PARCA_GIRISLERI", "parca_girisleri");
	define("DBT_BARKODSUZ_PARCA_GIRISLERI", "barkodsuz_parca_girisleri_icerik");



	define("DBT_PERSONEL", "personel");
	define("DBT_BASARISIZ_GIRISLER", "basarisiz_girisler");
	define("DBT_COOKIE_TOKENS", "cookie_tokens");
	define("DBT_AKTIVITE_KAYIT", "aktivite_kayit");


	define("DBT_ISEMRI_FORMLARI", "is_emri_formlari");
	define("DBT_ISEMRI_FORMU_CIKANLAR", "is_emri_formu_cikanlar");
	define("DBT_ISEMRI_FORMU_GIRENLER", "is_emri_formu_girenler");
	define("DBT_ISEMRI_FORMU_PERSONEL_DETAY", "is_emri_formu_personel_detay");


	define("DBT_REVIZYON_TALEPLERI", "revizyon_talepleri");
	define("DBT_REVIZYON_TALEP_TEKLIFLERI", "revizyon_talep_teklifleri");
	define("DBT_PARCA_TALEPLERI", "parca_talepleri");
	define("DBT_PARCA_TALEP_TEKLIFLERI", "parca_talep_teklifleri");

	define("DBT_STOK_FIRMARLAR", "stok_firmalar");
	define("DBT_OTOBUSLER", "otobusler");
	define("DBT_OTOBUS_MARKALAR", "otobus_markalar");
	define("DBT_OTOBUS_MODELLER", "otobus_modeller");



	define("MAIN_URL", "http://localhost/gitasWeb/");

    define("URL_ISEMRI_FORMLARI", MAIN_URL . "is_emri_formlari.php");


	define( "DIR_RES", MAIN_DIR . "res/"  );
	define( "DIR_RES_IMG", DIR_RES . "img/" );
	define( "URL_RES", MAIN_URL . "res/" );
	define( "URL_RES_IMG", URL_RES . "img/" );
	define( "URL_RES_CSS", URL_RES . "css/" );
	define( "URL_RES_JS", URL_RES . "js/" );
	define( "URL_RES_FONTS", URL_RES . "fonts/" );