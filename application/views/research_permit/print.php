<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Researh Permit</title>
	<link rel="stylesheet" href="<?= base_url(get_asset('vendors.css')) ?>">
	<link rel="stylesheet" href="<?= base_url(get_asset('app.css')) ?>">
    <style>
        @page {
            margin: 40px;
        }

        body {
            margin: 10px;
            background: none;
        }

        p {
            font-family: "Times New Roman";
            color:black;
        }
    </style>
</head>
<body>

<div style="margin-right: 50px;" >
    <div style="display: inline-block">
        <img src="<?= FCPATH . 'assets/dist/img/uin.png' ?>" width="60" height="100" >
    </div>
    <div style="display: inline-block;">
        <p style="margin-left: 10px;font-size: 18px; margin-bottom: 0; line-height: 1.1; text-align:center;">
            KEMENTERIAN AGAMA REPUBLIK INDONESIA
            UNIVERSITAS ISLAM NEGERI SUNAN KALIJAGA YOGYAKARTA
        </p>
        <p style="margin-left: 10px;font-size: 18px; margin-bottom: 0; line-height: 1.1; text-align:center;">
            <strong>FAKULTAS ILMU TARBIYAH DAN KEGURUAN</strong>
        </p>
        <p style="margin-left: 10px;font-size: 18px; margin-bottom: 0; line-height: 1.1; text-align:center;">
        Alamat: Jln. Marsda Adisucipto telepon 0274519739 fax 0274540971
            http://saintek.uin-suka.ac.id Yogyakarta 55281
        </p>
    </div>
</div>

<hr style="border: 2px solid black;margin-top: 0px;">

<table style="font-size: 14px; margin-top: 40px; margin-bottom: 10px; width: 100%;">
    <tbody>
    <tr>
        <th><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Nomor&nbsp;&nbsp;&nbsp;:
            </p>
            <p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Lamp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
            </p>
            <p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Hal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Permohonan Izin Pengambilan data
            </p>
        </th>
        <th><p style="font-weight: normal;">Yogyakarta, <?= $tanggalSekarang?></p></th>
    </tr>
    </tbody>
</table>


<p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    PO No :
</p>

<table style="margin-left:50px;font-size: 14px; margin-top: 40px; margin-bottom: 10px; width: 100%">
    <tbody>
    <tr>
        <th><p>Purchasing</p></th>
        <th><p>Finance</p></th>
    </tr>
    </tbody>
</table>

<!-- <script type="text/php">
    $x = 280;
    $y = 810;
    $text = "{PAGE_NUM} of {PAGE_COUNT}";
    $font = $fontMetrics->get_font("helvetica", "bold");
    $size = 10;
    $color = array(.08, .08, .08);
    $word_space = 0.0;  //  default
    $char_space = 0.0;  //  default
    $angle = 0.0;   //  default
    $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
</script> -->
</body>
</html>
