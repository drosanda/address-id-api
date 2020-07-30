<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Forgot Password</title>
		<link rel="stylesheet" type="text/css" href="//cdn.thecloudalert.com/assets/css/email.min.css" />
	</head>

	<body bgcolor="#FFFFFF">
		<table class="head-wrap" bgcolor="#ededed" width="100%" summary="header content">
			<tr>
				<th><h1>Forgot Password</h1></th>
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
									<h3>Hi {{fnama}}</h3>
									<p class="lead">It looks like you forgot your password.</p>
									<p>If you don't forget your password, please ignore this email.</p>
									<br />
									<p class="lead">If you want to reset your password, please follow this link</p>
									<p><a href="{{reset_link}}">{{reset_link}}</a></p>
								</td>
							</tr>
						</table>
						<p style="font-size: small; color: #ededed; font-style: italic;">Copyright © {{site_name}}, All rights reserved.</p>
					</div>
				</td>

				<td></td>
			</tr>
		</table>

	</body>
</html>
