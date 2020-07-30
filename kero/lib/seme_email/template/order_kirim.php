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
				<th><h1>YOUR ORDER IS ON ITS WAY</h1></th>
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
									<p class="lead">Pesanan Anda telah kami kirim. Paket akan tiba di alamat pengiriman akan sampai kira-kira 2 - 5 hari.</p>
									<p class="lead">No. Resi: {{order_noresi}} ({{order_kurir}})</p>
									<p>Jika Anda belum menerima pesanan Anda setelah waktu perkiraan tersebut, silakan hubungi kami via whatsapp +6281 318 855 001.</p>
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
