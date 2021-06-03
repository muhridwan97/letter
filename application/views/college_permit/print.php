<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Izin Kuliah</title>
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
        <img src="<?= FCPATH . 'assets/dist/img/uin.png' ?>" width="60" height="80" >
    </div>
    <div style="display: inline-block;">
        <p style="font-family:Likhan; font-size: 18px; margin-bottom: 0; line-height: 1.1; text-align:center;">
            KEMENTERIAN AGAMA
        </p><p style="font-family:Likhan; font-size: 18px; margin-bottom: 0; line-height: 1.1; text-align:center;">
            UNIVERSITAS ISLAM NEGERI SUNAN KALIJAGA
        </p>
        <p style="font-family:Likhan;font-size: 18px; margin-bottom: 0; line-height: 1.1; text-align:center;">
            FAKULTAS ILMU TARBIYAH DAN KEGURUAN
        </p>
        <p style="font-family:Likhan;font-size: 14px; margin-bottom: 0; line-height: 1.1; text-align:center;">
        Jl. Marsda Adisucipto Telp. (0274) 513056 Fax. (0274) 586117 Yogyakarta 55281
        </p>
    </div>
</div>

<hr style="border: 2px solid black;margin-top: 0px;">

<table style="font-size: 14px; margin-top: 40px; margin-bottom: 10px; width: 100%;">
    <tbody>
    <tr>
        <th><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Nomor&nbsp;&nbsp;&nbsp;: <?= $no_letter?>
            </p>
            <p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Hal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Permohonan Izin Kuliah
            </p>
            <p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Lamp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: -
            </p>
        </th>
        <th><p style="font-weight: normal;">Yogyakarta, <?= $tanggalSekarang?></p></th>
    </tr>
    </tbody>
</table>


<p style="margin-top: 40px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Kepada
</p>
<p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Yth. Dosen Pengampu Mata Kuliah <?= $mataKuliah ?>
</p>
<p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Di tempat
</p>
<p style="margin-top: 15px;margin-bottom: 5px; line-height: 1.3; font-size: 14px;">
    <i>Assalamu’alaikum Wr.Wb.</i>
</p>
<p style="margin-top: 15px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Dengan hormat,
</p>
<p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Dalam rangka pelaksanaan Kerja Program Studi Pendidikan Fisika, kami mengajukan permohonan
    agar mahasiswa-mahasiswa Program Studi Pendidikan Fisika berikut ini, dapat diijinkan tidak
    mengikuti perkuliahan yang diampu Bapak dan Ibu Dosen terhitung mulai tanggal <?= $tanggal ?>
</p>
<table style="margin-top: 10px;font-size: 14px; margin-bottom: 10px;border: 1px solid black;width: 100%">
    <tbody>
    <tr>
        <th style="text-align: center;border: 1px solid black;width: 20px"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            <strong>No</strong>
            </p>
        </th>
        <th style="text-align: center;border: 1px solid black;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                <strong>NIM</strong>
            </p>
        </th>
        <th style="text-align: center;border: 1px solid black;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            <strong>Nama</strong>
            </p>
        </th>
    </tr>
    <?php foreach ($students as $key => $student) : ?>
    <tr>
        <td style="text-align: center;border: 1px solid black;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                <?=$key+1 ?>
            </p>
        </td>
        <td style="text-align: left;border: 1px solid black;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            <?= $student['nim'];?>
            </p>
        </td>
        <td style="text-align: left;border: 1px solid black;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            <?= $student['nama'];?>
            </p>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px">
Demikian permohonan ini diajukan. Atas perhatian dan kerja sama yang diberikan, diucapkan terima
kasih.
</p>
<p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    <i>Wassalamu'alaikum Wr.Wb</i>
</p>
<table style="margin-left:180px;font-size: 14px; margin-top: 40px; margin-bottom: 10px; width: 100%">
    <tbody>
    <tr>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"></p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"></p>
            </th>
        <th style="width: 100px;"></th>
        <th style="text-align: left;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Mengetahui,</p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Kaprodi Pendidikan Fisika</p>
            </th>
    </tr>
    <tr>
        <th style="width: 100px;"></th>
        <th style="width: 100px;"></th>
        <th style="text-align: left;"><img src="data:image/png;base64,<?= $qrCodeKaprodi ?>"></th>
    </tr>
    <tr>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"></p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"></p>
            </th>
        <th style="width: 100px;"></th>
        <th style="text-align: left;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"><?= $kaprodi['name'] ?></p>
        <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">NIP. <?= $kaprodi['no_lecturer'] ?></p></th>
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
