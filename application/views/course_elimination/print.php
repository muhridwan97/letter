<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Hapus Mata Kuliah</title>
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
    <strong>PENGAJUAN PENGHAPUSAN MATAKULIAH</strong>
</p>

<table style="margin-left: 10px;font-size: 14px; margin-top: 40px; margin-bottom: 10px; width: 100%;">
    <tbody>
    <tr>
        <th><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Kepada Yth.
            </p>
            <p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Dekan
            </p>
            <p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Cq. Kabag. Tata usaha Fakultas
            </p>
            <p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Sains dan Teknologi
            </p>
            <p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                Di Yogyakarta
            </p>
        </th>
        <th><p style="text-align: right;font-weight: normal;">Yogyakarta, <?= $tanggalSekarang?></p></th>
    </tr>
    </tbody>
</table>

<p style="margin-left: 10px;margin-top: 20px;margin-bottom: 5px; line-height: 1.3; font-size: 14px;">
    <i>Assalamu’alaikum Wr.Wb.</i>
</p>

<p style="margin-left: 10px;margin-top: 15px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Saya yang bertandatangan di bawah ini:
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
    </tbody>
</table>
<p style="margin-left: 10px;margin-top: 15px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Mengajukan penghapusan mata kuliah-mata kuliah pilihan berikut ini:
</p>
<table style="margin-left: 10px;margin-top: 10px;font-size: 14px; margin-bottom: 10px;border: 1px solid black;width: 100%">
    <tbody>
    <tr>
        <th style="text-align: center;border: 1px solid black;width: 20px"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            <strong>No</strong>
            </p>
        </th>
        <th style="text-align: center;border: 1px solid black;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                <strong>Nama Mata Kuliah</strong>
            </p>
        </th>
        <th style="text-align: center;border: 1px solid black;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            <strong>SKS</strong>
            </p>
        </th>
        <th style="text-align: center;border: 1px solid black;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            <strong>Nilai</strong>
            </p>
        </th>
    </tr>
    <?php foreach ($courses as $key => $course) : ?>
    <tr>
        <td style="text-align: center;border: 1px solid black;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
                <?=$key+1 ?>
            </p>
        </td>
        <td style="text-align: left;border: 1px solid black;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            <?= $course['nama'];?>
            </p>
        </td>
        <td style="text-align: center;border: 1px solid black;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            <?= $course['sks'];?>
            </p>
        </td>
        <td style="text-align: center;border: 1px solid black;"><p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px;font-weight: normal;">
            <?= $course['nilai'];?>
            </p>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<p style="margin-left: 10px;margin-top: 15px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Sebagai bahan pertimbangan, saya telah menempuh mata kuliah total <?= $sks ?> sks dengan <?= $sks_pilihan ?> sks mata kuliah
    pilihan.
</p>
<p style="margin-left: 10px;margin-top: 15px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Demikian pengajuan ini dibuat dan saya bersedia menerima segala konsekuensinya. Atas
    perhatiannya, saya ucapkan terima kasih.
</p>
<p style="margin-left: 10px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    <i>Wassalamu'alaikum Wr.Wb</i>
</p>
<table style="font-size: 14px; margin-top: 40px; margin-bottom: 10px;table-layout: fixed; width: 100%">
    <tbody>
    <tr>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Menyetujui,</p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Kaprodi Pendidikan Fisika</p>
            </th>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Mengetahui,</p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Penasehat Akademik</p>
            </th>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">Mahasiswa</p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">yang bersangkutan</p>
            </th>
    </tr>
    <tr>
        <th style="text-align: center;"><img src="data:image/png;base64,<?= $qrCodeKaprodi ?>"></th>
        <th style="text-align: center;"><img src="data:image/png;base64,<?= $qrCodePembimbing ?>"></th>
        <th style="width: 100px;"></th>
    </tr>
    <tr>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"><?= $kaprodi['name'] ?></p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">NIP. <?= $kaprodi['no_lecturer'] ?></p>
            </th>
        <th style="text-align: center;"><p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;"><?= $pembimbing['name'] ?></p>
            <p style="line-height: 1.3;margin-bottom: 5px;font-size: 14px;font-weight: normal;">NIP. <?= $pembimbing['no_lecturer'] ?></p></th>
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
