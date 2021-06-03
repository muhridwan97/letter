<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Izin Penelitian</title>
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
                Lamp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: -
            </p>
            <p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Hal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Permohonan Izin Pengambilan data
            </p>
        </th>
        <th><p style="font-weight: normal;">Yogyakarta, <?= $tanggalSekarang?></p></th>
    </tr>
    </tbody>
</table>


<p style="margin-top: 40px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Yth.
</p>
<p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    <?= $terhormat ?>
</p>
<p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Di tempat
</p>
<p style="margin-top: 20px;margin-left: 60px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Assalamu’alaikum Wr.Wb.
</p>
<p style="margin-left: 60px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Kami beritahukan bahwa untuk kelengkapan penyusunan skripsi dengan judul :
</p>
<p style="margin-left: 60px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    <strong><?= $judul ?></strong>
</p>
<p style="margin-left: 60px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    diperlukan penelitian. Oleh karena itu, kami mengharap kiranya Bapak/Ibu berkenan
</p>
<p style="margin-left: 60px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    memberi izin kepada mahasiswa kami :
</p>
<table style="margin-left: 60px;font-size: 14px; margin-bottom: 5px;">
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
                Program studi
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
                Untuk pengambilan data
            </p>
        </th>
        <th style="text-align: right;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                :
            </p>
        </th>
        <th style="text-align: left;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                <?= $pengambilan_data ?>
            </p>
        </th>
    </tr>
    <tr>
        <th><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Metode pengumpulan data
            </p>
        </th>
        <th style="text-align: right;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                :
            </p>
        </th>
        <th style="text-align: left;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            <?= $metode ?>
            </p>
        </th>
    </tr>
    </tbody>
</table>
<p style="margin-left: 60px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Demikian, atas bantuan dan izin yang diberikan, kami mengucapkan terima kasih. 
</p>
<p style="margin-left: 60px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Wassalamu'alaikum Wr.Wb 
</p>
<p style="margin-top: 40px;margin-left: 80px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    a.n.Dekan
</p>
<table style="margin-left:20px;font-size: 14px; margin-top: 40px; margin-bottom: 10px; width: 100%">
    <tbody>
    <tr>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Ketua Program Studi</p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Pendidikan Fisika</p>
            </th>
        <th style="width: 100px;"></th>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Dosen Pembimbing Akademik</p></th>
    </tr>
    <tr>
        <th style="text-align: center;"><img src="data:image/png;base64,<?= $qrCodeKaprodi ?>"></th>
        <th style="width: 100px;"></th>
        <th style="text-align: center;"><img src="data:image/png;base64,<?= $qrCodePembimbing ?>"></th>
    </tr>
    <tr>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"><?= $kaprodi['name'] ?></p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">NIP. <?= $kaprodi['no_lecturer'] ?></p>
            </th>
        <th style="width: 100px;"></th>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"><?= $pembimbing['name'] ?></p>
        <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">NIP. <?= $pembimbing['no_lecturer'] ?></p></th>
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
