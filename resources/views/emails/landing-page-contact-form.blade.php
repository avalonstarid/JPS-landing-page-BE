<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Pesan Baru dari Formulir Landing Page</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
		  rel="stylesheet">

	<style>
        /* Reset CSS dasar */
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        /* Font Global */
        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            /* Fallback stack sangat penting di sini */
            font-family: 'Plus Jakarta Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
	</style>
</head>
<body style="background-color: #3A52A3; margin: 0; padding: 0;">

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="center" style="padding-top: 40px; padding-bottom: 40px;">

			<table border="0" cellpadding="0" cellspacing="0" width="600"
				   style="background-color: #ffffff; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">

				<tr>
					<td align="center" style="padding-top: 30px; padding-bottom: 10px;">
						<div style="background-color: #ffffff; border-radius: 50%; padding: 10px; display: inline-block;">
							<img src="{{ config('app.frontend_url') . '/icon/Logo-Janu-Putra.png' }}"
								 alt="{{ config('app.name') }}" width="80" style="display: block;">
						</div>
					</td>
				</tr>

				<tr>
					<td align="left" style="padding: 20px 40px 10px 40px;">
						<h1 style="margin: 0; font-size: 20px; color: #000000; font-weight: 600;">Pesan Baru dari
							Formulir Landing Page</h1>
					</td>
				</tr>

				<tr>
					<td align="left" style="padding: 10px 40px;">
						<h3 style="margin: 0 0 15px 0; font-size: 16px; color: #000000;">Detail Pengirim</h3>

						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="140" style="padding-bottom: 8px; color: #333333; font-weight: normal;">Nama
									Lengkap
								</td>
								<td style="padding-bottom: 8px; color: #000000; font-weight: 500;">
									: {{ $data['name'] }}
								</td>
							</tr>
							<tr>
								<td style="padding-bottom: 8px; color: #333333;">Alamat Email</td>
								<td style="padding-bottom: 8px; color: #000000; font-weight: 500;">
									: <a href="mailto:{{ $data['email'] }}"
										 style="color: #000000; text-decoration: none;">
										{{ $data['email'] }}
									</a>
								</td>
							</tr>
							<tr>
								<td style="padding-bottom: 8px; color: #333333;">Nomor Telepon</td>
								<td style="padding-bottom: 8px; color: #000000; font-weight: 500;">
									: {{ $data['phone'] }}
								</td>
							</tr>
							<tr>
								<td style="padding-bottom: 8px; color: #333333;">Lokasi/Kota</td>
								<td style="padding-bottom: 8px; color: #000000; font-weight: 500;">
									: {{ $data['location'] }}
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td align="left" style="padding: 20px 40px;">
						<h3 style="margin: 0 0 10px 0; font-size: 16px; color: #000000;">Isi Pesan</h3>
						<p style="margin: 0; line-height: 1.6; color: #333333;">
							{{ $data['message'] }}
						</p>
					</td>
				</tr>

				<tr>
					<td style="padding: 20px 0;">
						<hr style="border: 0; border-top: 1px solid #eeeeee;">
					</td>
				</tr>

				<tr>
					<td align="center" style="padding: 0 40px 40px 40px; color: #181D27; font-size: 14px;">
						<p style="margin: 0 0 10px 0;">&copy; {{ $data['company_name'] }} {{ date('Y') }}</p>

						<table border="0" cellpadding="0" cellspacing="0" align="center">
							<tr>
								<td style="vertical-align: middle;">
									<img src="https://cdn-icons-png.flaticon.com/512/542/542638.png" width="16"
										 height="16" alt="email" style="display: block; opacity: 0.6;">
								</td>
								<td style="vertical-align: middle; padding-left: 8px;">
									<a href="mailto:{{ $data['company_social']['email'] ?? '' }}"
									   style="color: #181D27; text-decoration: none;">{{ $data['company_social']['email'] ?? '' }}</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>

			</table>
			<div style="height: 40px;"></div>
		</td>
	</tr>
</table>

</body>
</html>
