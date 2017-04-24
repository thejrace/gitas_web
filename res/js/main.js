var Gitas = {};
Gitas.MAIN_URL = "http://localhost/gitasWeb/";
Gitas.AJAX_URL = Gitas.MAIN_URL + "ajax/";
Gitas.AJAX_REQ = {
    IS_EMRI_FORMLARI: Gitas.AJAX_URL + "is_emri_formlari.php",
    IS_EMRI_FORMU: Gitas.AJAX_URL + "is_emri_formu.php",
    OTOBUS: Gitas.AJAX_URL + "otobus.php",
    PARCA: Gitas.AJAX_URL + "parca.php",
    PARCA_GIRISI: Gitas.AJAX_URL + "parca_girisi.php",
    PARCA_TALEP: Gitas.AJAX_URL + "parca_talep.php",
    PARCA_TIPI: Gitas.AJAX_URL + "parca_tipi.php",
    SATICI_FIRMA: Gitas.AJAX_URL + "satici_firma.php",
    STOK: Gitas.AJAX_URL + "stok.php",
    REVIZYON_TALEPLERI: Gitas.AJAX_URL + "revizyon_talepleri.php"
};

var GPopup = function( options ){
    this.ison = false;
    this.popup = null;
    this.set_content = function( content ){
        this._popup.html( content );
    };
    this.on = function(){
        if( this.ison ) return;
        var thisref = this;
        this._popup = document.createElement('DIV');
        this._popup.className = "gitas-popup";
        this._popup = $(this._popup);
        $('#wrapper').append(this._popup);
        this._popup.dialog({
            title: options.baslik,
            width: 420,
            close: function(event, ui){
                $(this).dialog('destroy').remove();
                thisref.ison = false;
            }
        });
        this.content = options.content;
        this.set_content( options.content );
        this.ison = true;
    };
    this.off = function(){
        this._popup.dialog('destroy').remove();
        this.ison = false;
    }
};

function popup_form_error( form, ok, text ){
    var html;
    if( ok ){
        html = '<span class="ok">'+text+'</span>';
    } else {
        html = '<span class="bok">'+text+'</span>';
    }
    form.parent().find(".form-notf").html( html );
}

var Loader = {
    loader: null,
    on: function(){
        this.loader = $("#loader");
        this.loader.css({ top: document.body.scrollTop + "px" } );
        this.loader.slideDown(300);
        var loader_ref = this;
        $(window).scroll(function(){
            loader_ref.loader.css({ top: document.body.scrollTop + "px" } );
        });

        //setTimeout( function(){ loader.slideUp(100); loader.hide(); }, 1000 );
    },
    off: function(){
        this.loader.slideUp(100);
        this.loader.fadeOut();
    }
};

var GitasREQ = {
    default_req: function( url, data, cb ){
        Loader.on();
        $.ajax({
            type: "POST",
            url:url,
            dataType: 'json',
            data: data,
            success: function(res){
                Loader.off();
                console.log(res);
                if( typeof cb == 'function' ) cb( res );

            },
            error: function( jqXHR, textStatus, errorThrown ){
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    },
    // tekli isemri formu goruntuleme
    is_emri_formu_detay: function( item_id, cb ){
        this.default_req( Gitas.AJAX_REQ.IS_EMRI_FORMLARI, { req:"detay_al", form_id:item_id }, cb );
    },
    // tum is emri formlarini listeleme
    is_emri_formlari_dt: function( filter, cb ){
        this.default_req( Gitas.AJAX_REQ.IS_EMRI_FORMLARI, { req:"veri_al", filter:filter }, cb );
    },
    otobusler_dt: function( cb ){
        this.default_req( Gitas.AJAX_REQ.OTOBUS, { req:"veri_al" }, cb );
    },
    otobus_detay: function( item_id, cb ){
        this.default_req( Gitas.AJAX_REQ.OTOBUS, { req:"detay_al", item_id: item_id }, cb );
    },
    // ayarlar formunu al
    otobus_ayarlar: function( item_id, cb ){
        this.default_req( Gitas.AJAX_REQ.OTOBUS, { req:"ayarlar", item_id: item_id }, cb );
    },
    // ayarlar formunu gonder
    otobus_ayarlar_submit: function( data, cb ){
        this.default_req( Gitas.AJAX_REQ.OTOBUS, data, cb );
    },
    otobus_istatistik: function( item_id, cb ){
        this.default_req( Gitas.AJAX_REQ.OTOBUS, { req:"stats", item_id: item_id }, cb );
    },
    parca_tipi_ekle: function( data, cb ){
        this.default_req( Gitas.AJAX_REQ.PARCA_TIPI, data, cb );
    },
    // parca giris formu
    parca_giris_form: function( data, cb ){
        this.default_req( Gitas.AJAX_REQ.PARCA_GIRISI, data, cb );
    },
    parca_giris_detay: function( item_id, cb ){
        this.default_req( Gitas.AJAX_REQ.PARCA_GIRISI, { req:"detay_al", item_id: item_id }, cb );
    },
    parca_giris_dt: function( cb ){
        this.default_req( Gitas.AJAX_REQ.PARCA_GIRISI, { req:"veri_al" }, cb );
    },
    // parca tipi istatistik
    parca_tipi_istatistik: function( req, patip, cb ){
        this.default_req( Gitas.AJAX_REQ.PARCA_TIPI, { req:req, patip:patip }, cb );
    },
    parca_tipi_select: function( val, cb ){
        this.default_req( Gitas.AJAX_REQ.PARCA_TIPI, { req: "parca_tipi_select", parca_tipi: val }, cb );
    },
    parca_tipi_ayarlar: function( patip, cb ){
        this.default_req( Gitas.AJAX_REQ.PARCA_TIPI, { req:"parca_tipi_ayarlar", parca_tipi:patip }, cb );
    },
    parca_tipi_ayarlar_submit: function( data, cb ){
        this.default_req( Gitas.AJAX_REQ.PARCA_TIPI, data, cb );
    },
    parca_tipi_dt: function( cb ){
        this.default_req( Gitas.AJAX_REQ.PARCA_TIPI, { req:"veri_al" }, cb );
    },
    parca_tipi_genislet: function( patip, cb ){
        this.default_req( Gitas.AJAX_REQ.PARCA_TIPI, { req:"parca_veri_al", parca_tipi:patip }, cb );
    },
    parca_tipi_otobus_degisim_plan: function( patip, plaka, cb ){
        this.default_req( Gitas.AJAX_REQ.PARCA_TIPI, { req:"otobus_degisim_plan", patip:patip, plaka:plaka }, cb );
    },
    parca_detay: function( sk, cb ){
        this.default_req( Gitas.AJAX_REQ.PARCA, { req:"parca_detay", stok_kodu:sk }, cb );
    },
    satici_firma_ekle: function( data, cb ){
        this.default_req( Gitas.AJAX_REQ.SATICI_FIRMA, data, cb );
    },
    revizyon_talepleri_dt: function( cb ){
        this.default_req( Gitas.AJAX_REQ.REVIZYON_TALEPLERI, { req:"veri_al" }, cb );
    },
    revizyon_barkod_arama: function( sk, cb ){
        this.default_req( Gitas.AJAX_REQ.REVIZYON_TALEPLERI, { req:"barkod_arama", stok_kodu: sk }, cb );
    },
    revizyon_teklif_ekle: function( data, cb ){
        this.default_req( Gitas.AJAX_REQ.REVIZYON_TALEPLERI, data, cb );
    },
    revizyon_teklif_onayla: function( tid, cb ){
        this.default_req( Gitas.AJAX_REQ.REVIZYON_TALEPLERI, { req:'teklif_onayla', teklif_gid:tid }, cb );
    },
    revizyon_talep_tamamla: function( tid, cb ){
        this.default_req( Gitas.AJAX_REQ.REVIZYON_TALEPLERI, { req:'talep_tamamla', talep_gid:tid }, cb );
    },
    giris: function( data, cb ){
        this.default_req( "", data, cb );
    }
};

var GitasDT_CSS = {

    ICOS: [
        "parca", // parça tipi
        "otobus",
        "warning1",
        "formgri",
        "tickgri",
        "sepet",
        "surucubeyaz",
        "formyesil"
    ],
    ICO_SETS: [],
    COLOR_SETS: [
        "", // beyaz
        "ckirmizi",
        "csari",
        "cmavi",
        "cacikmavi",
        "cgri",
        "cturuncu",
        "cyesil"
    ],
    FONTS: [
        "flight",
        "fregular",
        "fsemibold",
        "fbold"
    ]

};

function init_row( data ){

    var html = "", right_content = "", icoset = "", rico = "", tarihcls = "";

    var content = '<div class="content">'+
        '<span class="col-ico"><i class="dtico '+GitasDT_CSS.ICOS[data.ico]+'"></i></span>'+
        '<span class="col-bigtitle '+GitasDT_CSS.COLOR_SETS[data.color]+' '+GitasDT_CSS.FONTS[data.font]+'">'+data.bigtitle+'</span>'+
        '<span class="col-subtitle '+GitasDT_CSS.COLOR_SETS[data.color]+'">'+data.subtitle+'</span>'+
        '</div>';

    if( data.right_content != undefined ){
        if( data.right_content.ico != undefined ){
            rico = '<span class="col-ico"><i class="dtico '+GitasDT_CSS.ICOS[data.right_content.ico]+'"></i></span>';
        } else {
            tarihcls = "tarih";
        }
        right_content = '<div class="right-content '+tarihcls+'">'+
            rico +
            '<span class="col-subtitle right '+GitasDT_CSS.COLOR_SETS[data.color]+'">'+data.right_content.text+'</span>'+
            '</div>';
    }
    if( data.icoset != undefined ){
        icoset = '<ul class="dtnav clearfix">';
        for( var j = 0; j < GitasDT_CSS.ICO_SETS[data.icoset].length; j++ ){
            icoset += '<li><button type="button" class="dtbtn dtico '+GitasDT_CSS.ICO_SETS[data.icoset][j]+'"></button></li>';
        }
        icoset += '</ul>';
    }
    if( data.part2 != undefined ){
        html = '<div class="part1 clearfix">' + content + icoset + right_content + '</div><div class="part2" style="background:#3d3d3d;">';
    } else {
        html = content + icoset + right_content;
    }
    var kompbut = "", kompdatarole = "";
    if( data.kompbut != undefined ){
        kompbut = "kompbut";
        kompdatarole = 'data-role="'+data.datarole+'"';
    }

    return '<tr data-id="'+data.data_id+'" data-key="'+data.bigtitle+'"  class="'+kompbut+'" '+kompdatarole+' ><td>'+html+'</td></tr>';
}

function init_stok_minitable( data ) {

    var tbody = "", item, revize_ico = "", barkodlu = false;
    for( var j = 0; j < data.length; j++ ){
        item = data[j];
        if( item.revize != undefined ){
            revize_ico = "";
            if( !barkodlu ) barkodlu = true;
            if( item.revize == "1") revize_ico += '<button type="button" class="mtbtn minitableico letrevize"></button>';
            tbody += '<tr data-id="'+item.stok_kodu+'">'+
                        '<td>'+item.aciklama+'</td>'+
                        '<td title="'+item.stok_kodu+'">'+item.stok_kodu.substr(0, 25)+'...</td>'+
                        '<td>'+revize_ico+'</td>'+
                        '<td><button type="button" class="mtbtn minitableico buyutec" btn-role="mtparcadata"></button></td>'+
                    '</tr>';

        } else {
            tbody += '<tr data-id="'+item.stok_kodu+'">'+
                '<td>'+item.aciklama+'</td>'+
                '<td title="'+item.miktar+'">'+item.miktar+'</td>'+
                '<td><button type="button" class="mtbtn minitableico buyutec" btn-role="mtparcadata"></button></td>'+
                '</tr>';
        }
    }
    if( barkodlu ){
        return '<div style="background:#3d3d3d;" class="minitable-container"><table class="minitable">'+
                    '<thead>'+
                        '<tr>'+
                            '<td>STOK KODU</td>'+
                            '<td>AÇIKLAMA</td>'+
                            '<td></td>'+
                            '<td></td>'+
                        '</tr>'+
                    '</thead>'+
                    '<tbody>'+ tbody +
                    '</tbody>'+
                '</table></div>';
    } else {
        return '<div style="background:#3d3d3d;" class="minitable-container"><table class="minitable">'+
            '<thead>'+
            '<tr>'+
            '<td>AÇIKLAMA</td>'+
            '<td>MİKTAR</td>'+
            '<td></td>'+
            '</tr>'+
            '</thead>'+
            '<tbody>'+ tbody +
            '</tbody>'+
            '</table></div>';
    }

}

$(function(){

    add_event( window, "scroll", function(){
        // çok uzunsa popup içeriği kaydırma yapma
        if( Popup.is_open() && window.innerHeight > $AH(Popup.popup).offsetHeight + 30 ) {
            css( $AH(Popup.popup), { top: document.body.scrollTop + Popup.top_gap + "px" });
        }
    });

});