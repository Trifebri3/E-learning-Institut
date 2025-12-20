<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Piagam {{ $piagam->serial_number }}</title>
    <style>
        html, body {
            width: 210mm; /* A4 portrait width */
            height: 297mm; /* A4 portrait height */
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Times New Roman', serif;
            background: url("{{ public_path('images/piagam_bg.png') }}") no-repeat top left;
            background-size: 100% 100%; /* full halaman portrait */
            position: relative;
        }

        .content {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20mm; /* agar teks tidak terlalu menempel ke tepi */
            box-sizing: border-box;
            color: #22470c;
            text-shadow: 0px 0px 2px rgba(255,255,255,0.7);
        }

        h1 {
            font-size: 20pt;
            margin: 5mm 0;
            text-align: left;
            padding-left: 25mm;

        }

        h2 {
            font-size: 20pt;
            margin: 3mm 0;
            text-align: left;
            padding-left: 25mm;
        }
        h4 {
            font-size: 14pt;
            margin: 2mm 0;
            text-align: left;
            padding-left: 25mm;
        }

        h3 {
            font-size: 16pt;
            margin: 3mm 0;
            text-align: left;
            padding-left: 25mm;
        }

        p {
            font-size: 14pt;
            margin: 2mm 0;
            text-align: left;
            padding-left: 25mm;
        }

        h5 {
            font-size: 12pt;
            margin: 2mm 0;
            text-align: right;
            padding-right: 35mm;
        }
        h6 {
            font-size: 10pt;
            margin: 1mm 0;
            text-align: right;
            padding-right: 35mm;
        }

.serial {
    margin-top: 5mm;
    font-size: 12pt;
    font-weight: bold;
    text-align: left;
    padding: 5px 10px;
    border: 2px double #2c3e50; /* border ganda */
    border-radius: 8px;          /* sudut melengkung */
    background-color: #f8f9fa;   /* warna latar agar menonjol */
    color: #1c1c1c;
    display: inline-block;
    letter-spacing: 1px;          /* agar terlihat lebih eksklusif */
}


        .footer {
            margin-top: 10mm;
            font-size: 12pt;
            text-align: right;
            line-height: 0.8;
            padding-right: 35mm;
        }

.ttd {
    margin-top: 15mm;       /* jarak dari konten atas */
    text-align: right;      /* semua teks rata kanan */
    width: 100%;            /* ambil seluruh lebar konten */
    line-height: 0.5;       /* rapatkan jarak antar teks */
    font-size: 12pt;
    padding-right: 35mm;    /* jarak dari tepi kanan */
}






    </style>
</head>
<body>
    <div class="content" style="width:100%; height:100%; padding:20mm; box-sizing:border-box; position:relative; font-family:'Times New Roman', serif;">
        <!-- Konten utama dalam kotak -->
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
            <h1 style="margin:10px 0;">Program {{ $piagam->program->title }}</h1>
            <br>
            <br>
            <h4 style="margin:5px 0;">Diberikan kepada</h4>
            <h2 style="margin:5px 0;">{{ $piagam->user->name }}</h2>
            <h3>Nomor ID: {{ $nomorInduk }}</h3>
            <p style="margin:5px 0;">Telah menyelesaikan E-Learning program</p>
            <p style="margin:5px 0;"><strong>{{ $piagam->program->title }}</strong></p>
            @if($piagam->grade)
                <p style="margin:5px 0;">Dengan Predikat: <strong>{{ $piagam->grade }}</strong></p>
            @endif
            <p style="margin:5px 0;">Selamat dan terus tingkatkan kompetensi Anda!</p>
            <!-- Serial number di kiri bawah -->
            <p class="serial">
                No. Serial: {{ $piagam->serial_number }}
            </p>

            <div class="footer">
                <p> Diterbitkan di <Strong>Bandung</Strong> </p>
                <p>
                    pada: {{ $piagam->issued_at->format('d M Y') }} </p>
            </div>


    </div>
    <!-- Tanda tangan di kanan bawah -->
</body>

</html>
