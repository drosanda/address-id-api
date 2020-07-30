<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Tagihan #{{order_id}}</title>
		<link rel="stylesheet" type="text/css" href="//cdn.thecloudalert.com/assets/css/email.min.css" />
	</head>
	<body bgcolor="#FFFFFF">
		<table class="head-wrap" bgcolor="#ededed" width="100%" summary="header content">
			<tr>
				<th><h1>ORDER INFORMATION</h1></th>
			</tr>
		</table>
		<table class="body-wrap" summary="body content">
			<tr>
				<td></td>
				<td class="container" bgcolor="#FFFFFF">

					<div class="content">
						<table width="100%">
							<tr>
								<td>
									<h3>Dear {{fnama}}</h3>
									<p class="lead">Terima kasih telah berbelanja di CALYSTA SKIN CARE. Berikut ini adalah Rincian Pesanan Anda.</p>
									<h4>DETAIL PESANAN</h4>
									<table class="" border="0" cellpadding="1" cellspacing="2" width="280" summary="detail pesanan content">
										<tr bgcolor="#EDEDED">
											<th align="left">TranID</th>
											<td align="left">:</td>
											<td align="left">#{{order_id}}</td>
										</tr>
										<tr bgcolor="#FFFFFF">
											<th align="left">Sub Total</th>
											<td align="left">:</td>
											<td align="left">{{order_sub_total}}</td>
										</tr>
										<tr bgcolor="#EDEDED">
											<th align="left">Ongkos Kirim</th>
											<td align="left">:</td>
											<td align="left">{{order_ongkir}}</td>
										</tr>
										<tr bgcolor="#FFFFFF">
											<th align="left">Grand Total</th>
											<td align="left">:</td>
											<td align="left">{{order_grand_total}}</td>
										</tr>
									</table>
									<br />
									<h4>PRODUK PESANAN</h4>
									{{order_produks}}
									<br />
									<br />
									<p>Kami akan memproses pengiriman barang setelah Anda menyelesaikan langkah-langkah berikut.</p>
									<h4>1.	LAKUKAN PEMBAYARAN</h4>
									<p>TranID anda adalah #{{order_id}} dengan Grand Total sebesar {{order_grand_total}}. Silakan transfer pembayaran Anda ke rekening kami.</p>

									<p>a.n. <b>{{bank_an}}</b>, No Rekening: <b>{{bank_norek}}</b>, Bank: <b>{{bank_nama}}</b></p>
									<br />
									<h4>2.	KONFIRMASI PEMBAYARAN ANDA</h4>
									<p>Setelah Anda mentransfer pembayaran, silakan lakukan konfirmasi dengan cara sebagai berikut:</p>
									<p>1.	Buka Aplikasi Android Calysta</p>
									<p>2.	Login ke Aplikasi</p>
									<p>3.	Klik tombol menu di ujung kiri atas, kemudian pilih Riwayat Order</p>
									<p>4.	Tekan tombol konfirmasi untuk order yang belum dikonfirmasi</p>
									<p>5. Kemudian lengkapi form konfirmasi pembayaran beserta foto Bukti Transfernya</p>
									<br />
									<p>Jika Anda tidak melakukan PEMBAYARAN dan KONFIRMASI dalam jangka waktu 2×24 jam setelah pesanan dibuat, pesanan Anda akan otomatis dibatalkan.</p>
									<p>Jika ada pertanyaan mengenai pembayaran, silakan hubungi kami via whatsapp +6281 318 855 001.</p>
								</td>
							</tr>
						</table>
					</div>

				</td>
				<td></td>
			</tr>
		</table>
	</body>
</html>
