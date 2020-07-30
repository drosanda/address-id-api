<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Order Expired</title>
		<link rel="stylesheet" type="text/css" href="//cdn.thecloudalert.com/assets/css/email.min.css" />
	</head>

	<body bgcolor="#FFFFFF">
		<table class="head-wrap" bgcolor="#ededed" width="100%" summary="header content">
			<tr>
				<th><h4>Order Expired</h4></th>
			</tr>
		</table>
		<table class="body-wrap" summary="body content">
			<tr>
				<td>&nbsp;</td>
				<td class="container" bgcolor="#FFFFFF">
					<div class="content">
						<table width="100%">
							<tr>
								<td>
									<h3>Hi {{fnama}},</h3>
									<p>Sorry, the seller cannot process your order for invoice: {{invoice_code}}. Your money will be automatically refunded within 2 days.</p>
									<p>&nbsp;</p>
									<p>&nbsp;</p>
									<p>Best wishes</p>
									<p>The {{site_name}} team</p>
								</td>
							</tr>
						</table>
						<p style="font-size: small; color: #ededed; font-style: italic;">Copyright © {{site_name}}, All rights reserved.</p>
					</div>
				</td>
				<td>&nbsp;</td>
			</tr>
		</table>

	</body>
</html>
