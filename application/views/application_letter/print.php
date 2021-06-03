<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Permohonan Habis Teori</title>
	<link rel="stylesheet" href="<?= base_url(get_asset('vendors.css')) ?>">
	<link rel="stylesheet" href="<?= base_url(get_asset('app.css')) ?>">
    <style>
        @page {
            margin: 40px;
        }

        body {
            margin: 10px;
            margin-left: 30px;
            margin-right: 30px;
            background: none;
        }

        p {
            font-family: "Times New Roman";
            color:black;
        }
    </style>
</head>
<body>

<p style="margin-left: 10px;font-size: 18px; margin-bottom: 0; line-height: 1.1; text-align:center;">
    <strong>PERMOHONAN HABIS TEORI</strong>
</p>

<p style="margin-left: 10px;margin-top: 60px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Yang bertandatangan di bawah ini, saya mahasiswa Fakultas Sains dan Teknologi UIN Sunan Kalijaga
    Yogyakarta,
</p>


<table style="margin-left: 10px;margin-top: 15px;font-size: 14px; margin-bottom: 5px;">
    <tbody>
    <tr>
        <th><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            Nama
            </p>
        </th>
        <th style="text-align: right;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                :
            </p>
        </th>
        <th style="text-align: left;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                <?= $nama ?>
            </p>
        </th>
    </tr>
    <tr>
        <th><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            Nomor Induk Mahasiswa
            </p>
        </th>
        <th style="text-align: right;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                :
            </p>
        </th>
        <th style="text-align: left;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                <?= $nim ?>
            </p>
        </th>
    </tr>
    <tr>
        <th><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            Semester
            </p>
        </th>
        <th style="text-align: right;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                :
            </p>
        </th>
        <th style="text-align: left;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                <?= $semester ?>
            </p>
        </th>
    </tr>
    <tr>
        <th><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            Jurusan / Program studi
            </p>
        </th>
        <th style="text-align: right;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                :
            </p>
        </th>
        <th style="text-align: left;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Pendidikan Fisika
            </p>
        </th>
    </tr>
    <tr>
        <th><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            Alamat
            </p>
        </th>
        <th style="text-align: right;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                :
            </p>
        </th>
        <th style="text-align: left;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                <?= $alamat ?>
            </p>
        </th>
    </tr>
    <tr>
        <th><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            Telp/HP
            </p>
        </th>
        <th style="text-align: right;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                :
            </p>
        </th>
        <th style="text-align: left;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            <?= $noTelepon ?>
            </p>
        </th>
    </tr>
    </tbody>
</table>
<p style="margin-left: 10px;margin-top: 15px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Bermaksud mengajukan Surat Keterangan Habis Teori kepada Dekan Fakultas Sains dan Teknologi
    untuk keperluan persyaratan akademik.
</p>
<table style="font-size: 14px; margin-top: 60px; margin-bottom: 10px;table-layout: fixed; width: 100%">
    <tbody>
    <tr>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Mengetahui,</p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Kaprodi Pendidikan Fisika</p>
            </th>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Yogyakarta, <?= $tanggalSekarang?></p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Yang mengajukan</p>
        </th>
    </tr>
    <tr>
        <th style="text-align: center;"><img src="data:image/png;base64,<?= $qrCodeKaprodi ?>"></th>
        <th style="width: 100px;"></th>
    </tr>
    <tr>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"><?= $kaprodi['name'] ?></p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">NIP. <?= $kaprodi['no_lecturer'] ?></p>
            </th>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"><?= $nama ?></p>
        <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">NIM. <?= $nim ?></p></th>
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
