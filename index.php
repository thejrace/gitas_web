<?php

	require 'inc/init.php';

//	var_dump(Session::get("test"));
//	var_dump(Session::get("test2"));



	require 'inc/header.php';


?>


	<div class="datatable">
		<div class="dtfilter">
		</div>

		<div class="dtcontent">
			<ul>
				

				<!--  STOK ROW  -->
				<li class="clearfix" data-id="15">
					<div class="part1 clearfix">
						<div class="content">
							<span class="col-ico"><i class="dtico parca"></i></span>
							<span class="col-bigtitle">KALİPER</span>
							<span class="col-subtitle">35 Adet</span>
							
						</div>

						
						
						<ul class="dtnav clearfix">
							<li><button type="button" class="dtbtn dtico stats" btn-role="parcastats"></button></li>
							<li><button type="button" class="dtbtn dtico talep" btn-role="parcatalep"></button></li>
							<li><button type="button" class="dtbtn dtico ayarlar" btn-role="parcaayarlar"></button></li>
							<li><button type="button" class="dtbtn dtico arti" btn-role="dtgenislet"></button></li>
						</ul>
						<div class="right-content">
							<span class="col-ico"><i class="dtico warning1"></i></span>
							<span class="col-subtitle right">Stok Kritik Seviyede</span>
						</div>
					</div>

					<div class="part2" style="background:#3d3d3d;">
						<div style="background:#3d3d3d;" class="minitable-container">
		
							<table class="minitable">
								<thead>
									<tr>
										<td>STOK KODU</td>
										<td>AÇIKLAMA</td>
										<td>FİRMA / TARİH</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>

									</tr>
								</thead>
								<tbody>
									<tr data-id="20">
										<td>GTSTRB324872193</td>
										<td>MAN T342 1.7 12V</td>
										<td>Obarey Turbo AŞ  /  12.01.2017</td>
										<td></td>
										<td><button type="button" class="mtbtn minitableico buyutec" btn-role="mtparcadata"></button></td>
										<td><button type="button" class="mtbtn minitableico edit" btn-role="mtparcaduzenle"></button></td>
										<td><button type="button" class="mtbtn minitableico sil" btn-role="mtparcasil"></button></td>
									</tr>
									<tr data-id="20">
										<td>GTSTR213872194</td>
										<td>MAN T342 1.7 12V</td>
										<td>Obarey Turbo AŞ  /  12.01.2017</td>
										<td><button type="button" class="mtbtn minitableico letrevize"></button></td>
										<td><button type="button" class="mtbtn minitableico buyutec" btn-role="mtparcadata"></button></td>
										<td><button type="button" class="mtbtn minitableico edit" btn-role="mtparcaduzenle"></button></td>
										<td><button type="button" class="mtbtn minitableico sil" btn-role="mtparcasil"></button></td>
									</tr>

								</tbody>
							</table>
						</div>
					</div>
				
				</li>

				<!--  STOK ROW2  -->
				<li class="clearfix" data-id="15">
					<div class="part1 clearfix">
						<div class="content">
							<span class="col-ico"><i class="dtico parca"></i></span>
							<span class="col-bigtitle">BALATA</span>
							<span class="col-subtitle">100 Adet</span>
							
						</div>

						
						
						<ul class="dtnav clearfix">
							<li><button type="button" class="dtbtn dtico stats" btn-role="parcastats"></button></li>
							<li><button type="button" class="dtbtn dtico talep" btn-role="parcatalep"></button></li>
							<li><button type="button" class="dtbtn dtico ayarlar" btn-role="parcaayarlar"></button></li>
							<li><button type="button" class="dtbtn dtico arti" btn-role="dtgenislet"></button></li>
						</ul>
						
					</div>

					<div class="part2" style="background:#3d3d3d;">
						<div style="background:#3d3d3d;" class="minitable-container">
		
							<table class="minitable">
								<thead>
									<tr>
										<td>AÇIKLAMA</td>
										<td>ADET</td>
										<td></td>
									</tr>
								</thead>
								<tbody>
									<tr data-id="20">
										<td>Sağ Ön</td>
										<td>25</td>
										<td><button type="button" class="mtbtn minitableico edit" btn-role="mtparcaduzenle"></button></td>
									</tr>
									<tr data-id="20">
										<td>Sol Ön</td>
										<td>25</td>
										<td><button type="button" class="mtbtn minitableico edit" btn-role="mtparcaduzenle"></button></td>
									</tr>
									<tr data-id="20">
										<td>Sağ Arka</td>
										<td>25</td>
										<td><button type="button" class="mtbtn minitableico edit" btn-role="mtparcaduzenle"></button></td>
									</tr>
									<tr data-id="20">
										<td>Sol Arka</td>
										<td>25</td>
										<td><button type="button" class="mtbtn minitableico edit" btn-role="mtparcaduzenle"></button></td>
									</tr>

								</tbody>
							</table>
						</div>
					</div>
				
				</li>

				<!-- PARÇA GİRİŞİ BARKODSUZ -->
				<li class="clearfix" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico parca"></i></span>
						<span class="col-bigtitle">BANT</span>
						<span class="col-subtitle">100 Adet</span>
					</div>
				</li>

				<!-- PARÇA GİRİŞİ BARKODLU -->
				<li class="clearfix"  data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico parca"></i></span>
						<span class="col-bigtitle">TURBO</span>
						<span class="col-subtitle">35 Adet</span>	
					</div>
					
					
					<div class="right-content">
						<span class="col-ico"><i class="dtico barkodsari"></i></span>
					</div>
				</li>

				<!-- PARÇA GİRİŞ ROW -->
				<li class="clearfix kompbut" kompbut-title="Parça Giriş Görüntüle" kompbut-data-type="parcagirisdata" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico sepet"></i></span>
						<span class="col-bigtitle cyesil">36 Adet</span>
						<span class="col-subtitle cyesil">Veli Konstantin</span>	
					</div>
					<div class="right-content">
						<span class="col-subtitle tarih">05-03-2017 14:35</span>
					</div>
				</li>

				<!-- PARÇA ÇIKIŞ ROW -->
				<li class="clearfix kompbut" kompbut-title="Parça Çıkış Görüntüle" kompbut-data-type="parcacikisdata" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico parca"></i></span>
						<span class="col-bigtitle">4 Adet -  34 YG 3483 #448</span>
						<span class="col-subtitle">Veli Konstantin</span>	
					</div>
					<div class="right-content">
						<span class="col-subtitle tarih">05-03-2017 14:35</span>
					</div>
				</li>

				<!--  OTOBUS ROW  -->
				<li class="clearfix" data-id="13">
					<div class="content">
						<span class="col-ico"><i class="dtico otobus"></i></span>
						<span class="col-bigtitle">34 YG 3856</span>
						<span class="col-subtitle">A-1636</span>
					</div>
					
					<ul class="dtnav clearfix">
						<li><button type="button" class="dtbtn dtico uyarizil" btn-role="otobusuyari"></button></li>
						<li><button type="button" class="dtbtn dtico letservis" btn-role="otobusdurum"></button></li>
						<li><button type="button" class="dtbtn dtico surucusari" btn-role="otobussurucu"></button></li>
						<li><button type="button" class="dtbtn dtico stats" btn-role="otobusstats"></button></li>
						<li><button type="button" class="dtbtn dtico parca" btn-role="otobusservis"></button></li>
						<li><button type="button" class="dtbtn dtico buyutec" btn-role="otobusdetay"></button></li>
						<li><button type="button" class="dtbtn dtico ayarlar" btn-role="otobusayar"></button></li> 
					</ul>
				</li>

				<!-- PARÇA TALEP ONAYLANDI ROW -->
				<li class="clearfix  kompbut" kompbut-title="Parça Talep Görüntüle" kompbut-data-type="parcatalepdata" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico talepsari"></i></span>
						<span class="col-bigtitle light csari">34 YP 3854 İEF ( 21.02.2017 )  - 2  Adet Turbo CFFF21</span>						
						<span class="col-subtitle csari"> - Talep Onaylandı</span>						
					</div>
					
					<div class="right-content">
						<span class="col-subtitle tarih csari">05-03-2017 14:35</span>
					</div>
				</li>

				<!-- PARÇA TALEP YAPILDI ROW -->
				<li class="clearfix  kompbut" kompbut-title="Parça Talep Görüntüle" kompbut-data-type="parcatalepdata" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico talepgri"></i></span>
						<span class="col-bigtitle light">34 YP 3854 İEF ( 21.02.2017 )  - 2  Adet Turbo CFFF21</span>						
						<span class="col-subtitle"> - Talep Yapıldı</span>						
					</div>
					
					<div class="right-content">
						<span class="col-subtitle tarih">05-03-2017 14:35</span>
					</div>
				</li>

				<!-- PARÇA TALEP TAMAMLANDI ROW -->
				<li class="clearfix  kompbut"  kompbut-title="Parça Talep Görüntüle" kompbut-data-type="parcatalepdata" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico tickgri"></i></span>
						<span class="col-bigtitle light cgri">34 YP 3854 İEF ( 21.02.2017 )  - 2  Adet Turbo CFFF21</span>						
						<span class="col-subtitle cgri"> - Tamamlandı</span>						
					</div>
					
					<div class="right-content">
						<span class="col-subtitle tarih cgri">05-03-2017 14:35</span>
					</div>
				</li>

				<!-- İŞEMRİ FORMU TASLAK -->
				<li class="clearfix  kompbut"  kompbut-title="İş Emri Formu Görüntüle" kompbut-data-type="isemridata" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico formgri"></i></span>
						<span class="col-bigtitle light">34 YP 5987</span>						
						<span class="col-subtitle"> - Parça bekleniyor.</span>						
					</div>
					
					<div class="right-content">
						<span class="col-subtitle tarih">05-03-2017 14:35</span>
					</div>
				</li>

				<!-- İŞEMRİ FORMU TAMAMLANDI  -->
				<li class="clearfix kompbut" kompbut-title="İş Emri Formu Görüntüle" kompbut-data-type="isemridata" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico tickgri"></i></span>
						<span class="col-bigtitle light cgri">34 YP 5987</span>						
						<span class="col-subtitle cgri"> - Tamamlandı.</span>						
					</div>
					
					<div class="right-content">
						<span class="col-subtitle tarih cgri">05-03-2017 14:35</span>
					</div>
				</li>

				<!--  UYARI KIRMIZI  -->
				<li class="clearfix kompbut" kompbut-title="Arıza Bildirim Görüntüle" kompbut-data-type="arizabildirimdata" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico warning1"></i></span>
						<span class="col-bigtitle light ckirmizi">34 YG 3856 ( A-1636 ) Arıza Bildirimi Yaptı</span>						
					</div>
					
					<div class="right-content">
						<span class="col-subtitle tarih ckirmizi">05-03-2017 14:35</span>
					</div>
				</li>

				<!-- UYARI BEYAZ -->
				<li class="clearfix kompbut"  kompbut-title="Gelen Mesaj Görüntüle" kompbut-data-type="msgin_yeni" data-id="15" >
					<div class="content">
						<span class="col-ico"><i class="dtico warning3"></i></span>
						<span class="col-bigtitle light">Veli Konstantin size bir mesaj gönderdi.</span>						
					</div>
					
					<div class="right-content">
						<span class="col-subtitle tarih">05-03-2017 11:35</span>
					</div>
				</li>

				<!-- UYARI SARI -->
				<li class="clearfix kompbut" kompbut-title="Otobüs Uyarı Görüntüleme" kompbut-data-type="otobusuyaridata" data-id="15" >
					<div class="content">
						<span class="col-ico"><i class="dtico warning2"></i></span>
						<span class="col-bigtitle light csari">34 YG 3827 Servis uyarıları var.</span>						
					</div>
					
					<div class="right-content">
						<span class="col-subtitle tarih csari">05-03-2017 11:35</span>
					</div>
				</li>

				<!-- UYARI ONAY YESIL -->
				<li class="clearfix kompbut" kompbut-title="Parça Talep Uyarı" kompbut-data-type="parcatalepdata" data-id="15" >
					<div class="content">
						<span class="col-ico"><i class="dtico tickyesil"></i></span>
						<span class="col-bigtitle light cyesil">34 YP 3593 İş Emri Formu ( 22.02.2017 ) parça talebi tamamlandı!</span>						
					</div>
					
					<div class="right-content">
						<span class="col-subtitle tarih cyesil">05-03-2017 11:35</span>
					</div>
				</li>

				<!--  TAKVİM TURUNCU  -->
				<li class="clearfix kompbut" kompbut-title="Takvim Kaydı Görüntüle" kompbut-data-type="takvimdata" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico saatturuncu"></i></span>
						<span class="col-bigtitle light cturuncu">34 YG 2841 TÜV Muayenesi</span>						
					</div>
					
					<div class="right-content">
						<span class="col-subtitle tarih cturuncu">05-03-2017 11:35</span>
					</div>
				</li>

				<!-- TAKVİM MAVİ -->
				<li class="clearfix kompbut" kompbut-title="Takvim Kaydı Görüntüle" kompbut-data-type="takvimdata" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico saatmavi"></i></span>
						<span class="col-bigtitle light cmavi">34 YG 2841 TÜV Muayenesi</span>						
					</div>
					
					<ul class="dtnav clearfix">
						<li><button type="button" class="dtbtn dtico loopyesil" btn-role="takvimloop"  ></button></li>
						<li><button type="button" class="dtbtn dtico powergri" btn-role="takvimpower"></button></li>
					</ul>
				</li>

				<!-- TAKVİM KIRMIZI -->
				<li class="clearfix kompbut" kompbut-title="Takvim Kaydı Görüntüle" kompbut-data-type="takvimdata" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico saatkirmizi"></i></span>
						<span class="col-bigtitle light ckirmizi">34 YG 2841 TÜV Muayenesi</span>						
					</div>
					
					<ul class="dtnav clearfix">
						<li><button type="button" class="dtbtn dtico loopyesil" btn-role="takvimloop"  ></button></li>
						<li><button type="button" class="dtbtn dtico powergri" btn-role="takvimpower"></button></li>
					</ul>
				</li>

				<!-- TAKVİM GRİ -->
				<li class="clearfix kompbut" kompbut-title="Takvim Kaydı Görüntüle" kompbut-data-type="takvimdata" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico saatgri"></i></span>
						<span class="col-bigtitle light cgri">34 YG 2841 TÜV Muayenesi</span>						
					</div>
					
					<ul class="dtnav clearfix">
						<li><button type="button" class="dtbtn dtico loopyesil" btn-role="takvimloop"  ></button></li>
						<li><button type="button" class="dtbtn dtico powergri" btn-role="takvimpower"></button></li>
					</ul>
				</li>

				<!-- MESAJ GELEN OKUNMAMIŞ -->
				<li class="clearfix kompbut" kompbut-title="Gelen Mesaj Görüntüle" kompbut-data-type="msgin_yeni" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico msginput_okunmadi"></i></span>
						<span class="col-bigtitle cacikmavi">Hüseyin Özbek</span>		
						<span class="col-subtitle cacikmavi">34 YP 6532 arıza sıklığı ve son servis hakkında rapor verin...</span>				
					</div>
					
					<div class="right-content">
						<span class="col-subtitle tarih cacikmavi">05-03-2017 11:35</span>
					</div>
				</li>

				<!-- MESAJ GELEN OKUNMUŞ -->
				<li class="clearfix kompbut" kompbut-title="Gelen Mesaj Görüntüle" kompbut-data-type="msgin_eski" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico msginput"></i></span>
						<span class="col-bigtitle cgri">Veli Konstantin</span>		
						<span class="col-subtitle">34 YP 6532 arıza sıklığı ve son servis hakkında rapor verin...</span>				
					</div>
					
					<div class="right-content">
						<span class="col-subtitle tarih cgri">05-03-2017 11:35</span>
					</div>
				</li>

				<!-- MESAJ GİDEN -->
				<li class="clearfix kompbut" kompbut-title="Giden Mesaj Görüntüle" kompbut-data-type="msgout" data-id="15" >
					<div class="content">
						<span class="col-ico"><i class="dtico msgout"></i></span>
						<span class="col-bigtitle">Hüseyin Özbek</span>		
						<span class="col-subtitle">34 YP 6532 arıza sıklığı ve son servis hakkında cevap</span>				
					</div>
					
					<div class="right-content">
						<span class="col-subtitle tarih">05-03-2017 12:35</span>
					</div>
				</li>


				<!--  İŞEMRİ PERSONEL İŞ  -->
				<li class="clearfix" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico personelgri"></i></span>
						<span class="col-bigtitle light">Veli Konstantin</span>						
					</div>
					
					<ul class="dtnav clearfix">
						<li><button type="button" class="dtbtn dtico editmor" btn-role="personelisedit"  ></button></li>
						<li><button type="button" class="dtbtn dtico carpikirmizi" btn-role="personelissil"></button></li>
					</ul>
				</li>

				<!--  ÇIKAN PARÇA HURDA  -->
				<li class="clearfix" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico artikucuk"></i></span>
						<span class="col-ico"><i class="dtico lethurdakucuk"></i></span>
						<span class="col-bigtitle light ckirmizi">XX18322193 H1 Ampul</span>						
					</div>
					
					<ul class="dtnav clearfix">
						<li><button type="button" class="dtbtn dtico carpikirmizi" btn-role="cikanparcasil"></button></li>
					</ul>
				</li>

				<!--  ÇIKAN PARÇA REVIZE  -->
				<li class="clearfix" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico artikucuk"></i></span>
						<span class="col-ico"><i class="dtico letrevizekucuk"></i></span>
						<span class="col-bigtitle light cmavi">XX18322193 H1 Ampul</span>						
					</div>
					
					<ul class="dtnav clearfix">
						<li><button type="button" class="dtbtn dtico carpikirmizi" btn-role="cikanparcasil"></button></li>
					</ul>
				</li>

				<!--  GİREN PARÇA REVIZE  -->
				<li class="clearfix" data-id="15">
					<div class="content">
						<span class="col-ico"><i class="dtico eksikucuk"></i></span>
						<span class="col-bigtitle light">XX18322193 H1 Ampul</span>						
					</div>
					
					<ul class="dtnav clearfix">
						<li><button type="button" class="dtbtn dtico carpikirmizi" btn-role="girenparcasil"></button></li>
					</ul>
				</li>



			</ul>
		</div>
	</div>


	<div style="margin-top:40px;" >

		<div class="section">
			<div class="section-header">
				İŞ EMRİ FORMU
			</div>
			<div class="section-content">
				<div class="form">
					<form action="" method="POST">


                        <div class="form-section-header">ARAÇ DETAYLARI</div>

						<div class="input-container au">
							<label>Plaka</label>
							<input type="text" />
                            <div class="overlay-loader" style="display:none"><img src="<?php echo URL_RES_IMG ?>rolling.gif" /></div>
						</div>
						<div class="input-row">
							<div class="input-container au">
								<label>Ruhsat Kapı No</label>
								<input type="text" />
							</div>

							<div class="input-container au">
								<label>Geliş KM</label>
								<input type="text" />
							</div>

							<div class="input-container au">
								<label>Geliş Tarih</label>
								<input type="text" />
							</div>
						</div>

						<div class="input-row">
							<div class="input-container au">
								<label>Aktif Kapı No</label>
								<input type="text" />
							</div>

							<div class="input-container au">
								<label>Sürücü</label>
								<input type="text" />
							</div>

							<div class="input-container au">
								<label>Çıkış Tarih</label>
								<input type="text" />
							</div>
						</div>


                        <div class="form-section-header">ARIZA DETAYLARI</div>

                        <div class="input-container au">
                            <label>Arıza Detayları</label>
                            <textarea class="full"></textarea>
                        </div>

                        <div class="input-container au">
                            <label>Arıza Tespiti ve Nedeni</label>
                            <textarea class="full"></textarea>
                        </div>

                        <div class="input-container au">
                            <label>Yapılan Onarım / İyileştirme Önerisi</label>
                            <textarea class="full"></textarea>
                        </div>





                        <div class="float-form clearfix">
                            <div class="form-section-header">STOK KULLANIM</div>

                            <div class="form-section-33">
                                <div class="form-section-header kisa">BALATA</div>
                                <div class="form-section-content">

                                    <div class="input-container au cb">
                                        <input type="checkbox" />
                                        <label>Sağ Ön</label>
                                    </div>

                                    <div class="input-container au cb">
                                        <input type="checkbox" />
                                        <label>Sağ Arka</label>
                                    </div>


                                    <div class="input-container au cb">
                                        <input type="checkbox" />
                                        <label>Sol Ön</label>
                                    </div>

                                    <div class="input-container au cb">
                                        <input type="checkbox" />
                                        <label>Sol Arka</label>
                                    </div>

                                </div>
                            </div>
                            <div class="form-section-33">
                                <div class="form-section-header kisa">YAĞ</div>
                                <div class="form-section-content">

                                    <div class="input-row">
                                        <div class="input-container au">
                                            <label>Diferansiyel</label>
                                            <input type="text" class="kisa" />
                                        </div>

                                        <div class="input-container au">
                                            <label>Motor</label>
                                            <input type="text" class="kisa" />
                                        </div>
                                    </div>

                                    <div class="input-row">
                                        <div class="input-container au">
                                            <label>Şanzıman</label>
                                            <input type="text" class="kisa" />
                                        </div>

                                        <div class="input-container au">
                                            <label>Direksiyon</label>
                                            <input type="text" class="kisa" />
                                        </div>
                                    </div>

                                    <div class="input-row">
                                        <div class="input-container au">
                                            <label>Gres</label>
                                            <input type="text" class="kisa" />
                                        </div>

                                        <div class="input-container au">
                                            <label>Sıvı Gres</label>
                                            <input type="text" class="kisa" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-section-33">
                                <div class="form-section-header kisa">MUHTELİF</div>
                                <div class="form-section-content">

                                    <div class="input-row">
                                        <div class="input-container au">
                                            <label>Antifriz</label>
                                            <input type="text" class="kisa" />
                                        </div>

                                        <div class="input-container au">
                                            <label>Balata Spreyi</label>
                                            <input type="text" class="kisa" />
                                        </div>
                                    </div>

                                    <div class="input-row">
                                       <div class="input-container au">
                                            <label>Silikon</label>
                                            <input type="text" class="kisa" />
                                        </div>

                                        <div class="input-container au">
                                            <label>Bant</label>
                                            <input type="text" class="kisa" />
                                        </div>
                                    </div>


                                </div>

                            </div>
                        </div>

                        <div class="form-section-header full mtop-20">STOK</div>
                        <div class="form-section-nav">
                            <button type="button" class="mnbtn acikgri">ÇIKAN PARÇA</button>
                            <button type="button" class="mnbtn acikgri">GİREN PARÇA</button>
                        </div>

                        <div class="datatable">
                            <div class="dtfilter">
                            </div>

                            <div class="dtcontent">
                                <ul>
                                    <!--  ÇIKAN PARÇA HURDA  -->
                                    <li class="clearfix" data-id="15">
                                        <div class="content">
                                            <span class="col-ico"><i class="dtico artikucuk"></i></span>
                                            <span class="col-ico"><i class="dtico lethurdakucuk"></i></span>
                                            <span class="col-bigtitle light ckirmizi">XX18322193 H1 Ampul</span>
                                        </div>

                                        <ul class="dtnav clearfix">
                                            <li><button type="button" class="dtbtn dtico carpikirmizi" btn-role="cikanparcasil"></button></li>
                                        </ul>
                                    </li>

                                    <!--  ÇIKAN PARÇA REVIZE  -->
                                    <li class="clearfix" data-id="15">
                                        <div class="content">
                                            <span class="col-ico"><i class="dtico artikucuk"></i></span>
                                            <span class="col-ico"><i class="dtico letrevizekucuk"></i></span>
                                            <span class="col-bigtitle light cmavi">XX18322193 H1 Ampul</span>
                                        </div>

                                        <ul class="dtnav clearfix">
                                            <li><button type="button" class="dtbtn dtico carpikirmizi" btn-role="cikanparcasil"></button></li>
                                        </ul>
                                    </li>

                                    <!--  GİREN PARÇA REVIZE  -->
                                    <li class="clearfix" data-id="15">
                                        <div class="content">
                                            <span class="col-ico"><i class="dtico eksikucuk"></i></span>
                                            <span class="col-bigtitle light">XX18322193 H1 Ampul</span>
                                        </div>

                                        <ul class="dtnav clearfix">
                                            <li><button type="button" class="dtbtn dtico carpikirmizi" btn-role="girenparcasil"></button></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>


                        <div class="form-section-header">PERSONEL BİLGİLERİ</div>

                        <div class="form-section-nav">
                            <button type="button" class="mnbtn acikgri">PERSONEL EKLE</button>
                        </div>

                        <div class="datatable">
                            <div class="dtfilter">
                            </div>

                            <div class="dtcontent">
                                <ul>
                                    <!--  İŞEMRİ PERSONEL İŞ  -->
                                    <li class="clearfix" data-id="15">
                                        <div class="content">
                                            <span class="col-ico"><i class="dtico personelgri"></i></span>
                                            <span class="col-bigtitle light">Veli Konstantin</span>
                                        </div>

                                        <ul class="dtnav clearfix">
                                            <li><button type="button" class="dtbtn dtico editmor" btn-role="personelisedit"  ></button></li>
                                            <li><button type="button" class="dtbtn dtico carpikirmizi" btn-role="personelissil"></button></li>
                                        </ul>
                                    </li>

                                </ul>
                            </div>
                        </div>


                        <div class="nav">
                            <button type="button" class="mnbtn gri">TASLAK OLARAK KAYDET</button>
                            <button type="button" class="mnbtn mor">TAMAMLA VE YAZDIR</button>
                        </div>

					</form>
				</div>
			</div>
		</div>
		
	</div>

	<script type="text/javascript">

		$(document).ready(function() {
		    $('table.minitable').DataTable();


		    $(document).on('click', '.kompbut', function(){
		    	var _this = $(this),
		    		_popup = document.createElement('DIV');

		    	_popup.className = "gitas-popup";
		    	$('#wrapper').append(_popup);
		    	$(_popup).dialog({
		    		title: _this.attr('kompbut-title'),
		    		close: function(event, ui){
		    			$(this).dialog('destroy').remove()
		    		}
		    	});

		    	console.log("Kompbut Type: " + _this.attr('kompbut-data-type') );
		    	console.log("Kompbut Data ID: " + _this.attr('data-id') );

		    });

		    $(document).on('click', '.dtbtn', function(event){

		    	var _this = $(this),
		    		_parent = $(_this.parent().parent().parent()),
		    		btn_role = _this.attr("btn-role"),
		    		data_id  = _parent.attr('data-id');

		    	// eger genislet - kapat seklindeyse 4. parent li oluyor
		    	if( data_id == undefined ) {
		    	    _parent = _parent.parent();
		    	    data_id = _parent.attr('data-id');
                }

		    	
				console.log( "BTN ROLE " +  btn_role + " DATAID: " + data_id );

		    	if( btn_role == 'dtgenislet' ){
		    		if( _this.hasClass('arti') ){
		    			_this.addClass('eksi');
		    			_this.removeClass('arti');
		    			_parent.find('.part2').fadeIn();
		    		} else {
		    			_this.addClass('arti');
		    			_this.removeClass('eksi');
		    			_parent.find('.part2').hide();
		    		}
		    	} 
		    	

		    	event.stopPropagation();

		    });

		});



	</script>

<?php
	require 'inc/footer.php';