<?php

/**
 * Created by PhpStorm.
 * User: Jeppe
 * Date: 09.04.2017
 * Time: 21:09
 */
class GitasDT_CSS {

    public static
        // ico setleri
        $ICOSET_PARCA_TIPI = 0,
        $ICOSET_OTOBUS = 1,
        $ICOSET_PATIP_OTOBUS_ISTATISTIK = 2,
        $ICOSET_REVIZYON_TALEP = 3,
        $ICOSET_VARYANT = 4,
        $ICOSET_VARYANT_PG = 5,
        $ICOSET_PARCA_TIPI_ARTISIZ = 6,

        // tekli icolar
        $ICO_PARCA_TIPI = 0,
        $ICO_OTOBUS = 1,
        $ICO_WARNING1 = 2,
        $ICO_IEF_GRI = 3,
        $ICO_TICK_GRI = 4,
        $ICO_SEPET = 5,
        $ICO_SURUCUBEYAZ = 6,
        $ICO_IEF_YESIL = 7,
        $ICO_VARYANT = 8,


        // renk classlari
        $C_BEYAZ = 0,
        $C_KIRMIZI = 1,
        $C_SARI = 2,
        $C_MAVI = 3,
        $C_ACIK_MAVI = 4,
        $C_GRI = 5,
        $C_TURUNCU = 6,
        $C_YESIL = 7,

        // font classlari
        $F_LIGHT = 0,
        $F_REGULAR = 1,
        $F_SEMIBOLD = 2,
        $F_BOLD = 3;

    public static function js_out( $seviye ){

        if( $seviye == Personel::$ADMIN ){
            return  '[[ "stats", "talep", "ayarlar", "arti" ], // parça tipi,
                    [ "surucusari", "stats", "parca", "buyutec", "ayarlar" ], 
                    [ "arti" ],
                    [ "sepet", "buyutec" ],
                    [ "editmor", "arti" ],
                    [ "tickgri", "arti" ],
                    [ "stats", "talep", "ayarlar" ] ]';
        } else if( $seviye == Personel::$MUHASEBE ){
            return  '[[ "stats", "talep", "ayarlar", "arti" ], // parça tipi,
                    [ "surucusari", "stats", "parca", "buyutec", "ayarlar" ], 
                    [ "arti" ],
                    [ "sepet", "buyutec" ],
                     [ "ico_dt_editmor", "arti" ],
                     [ "tickgri", "arti" ],
                     [ "stats", "talep", "ayarlar" ]]';
        } else if( $seviye == Personel::$SERVIS ){
            return  '[[ "stats", "talep", "arti" ], // parça tipi,
                    [ "stats", "parca", "buyutec" ], // otobus
                    [ "arti" ],
                    [ "buyutec" ],
                    [ "stats", "talep" ]]';
        } else if( $seviye == Personel::$SURUCU ){
            return  '[[ ], // parça tipi,
                    [  ], // otobus
                    [],
                    [ ]]';
        }

    }

}
