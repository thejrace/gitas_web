var Gitas = {};
Gitas.MAIN_URL = "http://localhost/gitasWeb/";
Gitas.AJAX_URL = Gitas.MAIN_URL + "ajax/";

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

var GitasDT_CSS = {

    ICOS: [
        "parca", // parça tipi
        "otobus",
        "warning1",
        "formgri",
        "tickgri",
        "sepet"


    ],
    ICO_SETS: [
        [ "stats", "talep", "ayarlar", "arti" ], // parça tipi,
        [ "surucusari", "stats", "parca", "buyutec", "ayarlar" ] // otobus
    ],
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