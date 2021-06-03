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
<div style="margin-right: 50px;" >
    <div style="display: inline-block">
        <p style="font-family:Likhan; font-size: 12px; margin-bottom: 0; line-height: 1.1; text-align:left;">  
        <img src="<?= FCPATH . 'assets/dist/img/uin.png' ?>" width="20" height="25" ><strong>Universitas Islam Negeri Sunan Kalijaga</strong>
        </p>
    </div>
</div>
<p style="margin-left: 10px;font-size: 14px; margin-top: 20px;margin-bottom: 0; line-height: 1.1; text-align:center;">
    <strong><u>PENUNJUKAN PEMBIMBING SKRIPSI</u></strong>
</p>
<table style="font-size: 14px; margin-top: 40px; margin-bottom: 10px; width: 100%;">
    <tbody>
    <tr>
        <th><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= $no_letter?>
            </p>
            <p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Hal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <strong>Penunjukan Pembimbing</strong>
            </p>
        </th>
    </tr>
    </tbody>
</table>
<p style="margin-left: 30px;margin-top: 10px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Kepada Yth.
</p>
<p style="margin-left: 30px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    <?= $pembimbing['name'] ?>
</p>
<p style="margin-left: 30px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    di Tempat
</p>
<p style="margin-left: 30px;margin-top: 15px;margin-bottom: 5px; line-height: 1.3; font-size: 14px;">
    <i>Assalamu’alaikum Wr.Wb.</i>
</p>
<p style="margin-left: 30px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Dengan hormat,
</p>
<p style="margin-left: 10px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Berdasarkan rapat koordinasi dosen Program Studi Pendidikan Fisika pada tanggal <?= $tanggal ?> tentang outline skripsi mahasiswa, kami menunjuk
    Bapak/Ibu sebagai pembimbing skripsi mahasiswa berikut.
</p>


<table style="margin-left: 30px;font-size: 14px; margin-bottom: 5px;">
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
            NIM
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
            Prodi/Smt
            </p>
        </th>
        <th style="text-align: right;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                :
            </p>
        </th>
        <th style="text-align: left;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Pendidikan Fisika/<?= $semester ?>
            </p>
        </th>
    </tr>
    <tr>
        <th><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            Fakultas
            </p>
        </th>
        <th style="text-align: right;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                :
            </p>
        </th>
        <th style="text-align: left;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            Ilmu Tarbiyah dan Keguruan UIN Sunan Kalijaga Yogyakarta
            </p>
        </th>
    </tr>
    <tr>
        <th><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            Judul Skripsi
            </p>
        </th>
        <th style="text-align: right;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                :
            </p>
        </th>
        <th style="text-align: left;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                <strong><?= $judul ?></strong>
            </p>
        </th>
    </tr>
    </tbody>
</table>
<p style="margin-left: 10px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Demikian surat ini kami sampaikan, kami berharap Ibu dapat segera mengarahkan dan
    membimbing mahasiswa tersebut untuk menyusun skripsi. Atas perhatiannya, kami
    menyampaikan terima kasih.
</p>

<p style="margin-left: 30px;margin-top: 15px;margin-bottom: 5px; line-height: 1.3; font-size: 14px;">
    <i>Wassalaamu’alaikum wr.wb.</i>
</p>
<table style="margin-left:160px;font-size: 14px; margin-top: 60px; margin-bottom: 10px; width: 100%">
    <tbody>
    <tr>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"></p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"></p>
            </th>
        <th style="width: 100px;"></th>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Yogyakarta, <?= $tanggalSekarang?></p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Kaprodi Pendidikan Fisika</p>
        </th>
    </tr>
    <tr>
        <th style="width: 100px;"></th>
        <th style="width: 100px;"></th>
        <th style="text-align: center;"><img src="data:image/png;base64,<?= $qrCodeKaprodi ?>"></th>
    </tr>
    <tr>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"></p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"></p>
            </th>
        <th style="width: 100px;"></th>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"><u><?= $kaprodi['name'] ?></u></p>
        <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">NIP. <?= $kaprodi['no_lecturer'] ?></th>
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
