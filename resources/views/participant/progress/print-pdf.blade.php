<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Progress Program - {{ $program->title }}</title>
    <style>
        /* RESET DAN BASE STYLING UNTUK PDF */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000000;
            background: #ffffff;
            margin: 15mm 10mm 15mm 10mm; /* Top Right Bottom Left */
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 190mm; /* A4 width minus margins */
            margin: 0 auto;
        }

        /* HEADER STYLES */
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000000;
        }

        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 11pt;
            color: #333333;
        }

        /* TABLE STYLES */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10pt;
        }

        .info-table td {
            padding: 6px 4px;
            vertical-align: top;
            border: none;
        }

        .info-table .label {
            font-weight: bold;
            width: 30%;
            color: #333333;
        }

        /* SUMMARY GRID */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            margin: 12px 0;
        }

        .summary-item {
            text-align: center;
            padding: 8px 4px;
            border: 1px solid #cccccc;
            border-radius: 2px;
            background: #f9f9f9;
        }

        .summary-value {
            font-size: 12pt;
            font-weight: bold;
            margin: 3px 0;
        }

        .summary-label {
            font-size: 8pt;
            color: #666666;
        }

        /* CLASS SECTION */
        .class-section {
            margin: 20px 0;
            page-break-inside: avoid;
        }

        .class-header {
            background: #333333;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            border-radius: 2px 2px 0 0;
            font-size: 10pt;
        }

        .component-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 9pt;
        }

        .component-table th,
        .component-table td {
            border: 1px solid #dddddd;
            padding: 6px;
            text-align: left;
        }

        .component-table th {
            background: #f5f5f5;
            font-weight: bold;
        }

        .component-table tr:nth-child(even) {
            background: #fafafa;
        }

        /* FINAL SCORE */
        .final-score {
            text-align: center;
            margin: 15px 0;
            padding: 12px;
            border: 2px solid #000000;
            border-radius: 4px;
            background: #f0f0f0;
        }

        .final-score-value {
            font-size: 18pt;
            font-weight: bold;
            margin: 8px 0;
        }

        /* STATUS BADGES */
        .status-lulus {
            color: #006600;
            font-weight: bold;
        }

        .status-memenuhi {
            color: #996600;
            font-weight: bold;
        }

        .status-tidak-lulus {
            color: #990000;
            font-weight: bold;
        }

        /* PRINT/PDF OPTIMIZATION */
        @media print {
            body {
                font-size: 10pt;
                margin: 10mm 8mm 10mm 8mm;
            }

            .page-break {
                page-break-before: always;
            }

            .avoid-break {
                page-break-inside: avoid;
            }

            .summary-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* UTILITY CLASSES */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mt-5 { margin-top: 5px; }
        .mt-10 { margin-top: 10px; }
        .mt-15 { margin-top: 15px; }
        .mb-5 { margin-bottom: 5px; }
        .mb-10 { margin-bottom: 10px; }
        .mb-15 { margin-bottom: 15px; }
        .p-5 { padding: 5px; }
        .p-8 { padding: 8px; }
        .p-10 { padding: 10px; }
        .border { border: 1px solid #cccccc; }
        .border-t { border-top: 1px solid #cccccc; }
        .border-b { border-bottom: 1px solid #cccccc; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Dokumen -->
        <div class="header">
            <h1>LAPORAN PROGRESS PROGRAM</h1>
            <p>SISTEM PEMBELAJARAN E-LEARNING</p>
        </div>

        <!-- Informasi Dokumen -->
        <table class="info-table">
            <tr>
                <td class="label">Nomor Dokumen</td>
                <td>: LPP/{{ $program->id }}/{{ now()->format('Ym') }}/{{ $user->id }}</td>
                <td class="label">Tanggal Terbit</td>
                <td>: {{ now()->translatedFormat('d F Y') }}</td>
            </tr>
        </table>

        <!-- Informasi Peserta & Program -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <!-- Informasi Peserta -->
            <div class="border p-8">
                <h3 style="font-size: 10pt; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid #ccc; padding-bottom: 4px;">
                    INFORMASI PESERTA
                </h3>
                <table class="info-table">
                    <tr>
                        <td class="label">Nama Lengkap</td>
                        <td>: {{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email</td>
                        <td>: {{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td class="label">ID Peserta</td>
<td>: {{ \App\Models\NomorInduk::where('user_id', $user->id)
        ->where('program_id', $program->id)
        ->first()?->nomor_induk ?? '-' }}
</td>


                    </tr>
                </table>
            </div>

            <!-- Informasi Program -->
            <div class="border p-8">
                <h3 style="font-size: 10pt; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid #ccc; padding-bottom: 4px;">
                    INFORMASI PROGRAM
                </h3>
                <table class="info-table">
                    <tr>
                        <td class="label">Program</td>
                        <td>: {{ $program->title }}</td>
                    </tr>
                    <tr>
                        <td class="label">Total Kelas</td>
                        <td>: {{ $kelasCount }} Kelas</td>
                    </tr>
                    <tr>
                        <td class="label">Periode Laporan</td>
                        <td>: {{ now()->translatedFormat('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Ringkasan Progress -->
        <div class="border p-8 mb-15">
            <h3 class="text-center font-bold mb-8" style="font-size: 11pt;">RINGKASAN PROGRESS</h3>

            <div class="summary-grid">
                @php
                    $components = [
                        'assignment' => 'Tugas',
                        'quiz' => 'Quiz',
                        'essay' => 'Esai',
                        'presensi' => 'Presensi',
                        'learning' => 'Learning Path',
                        'video' => 'Video',
                        'modul' => 'Modul',
                        'custom' => 'Tambahan'
                    ];
                @endphp

                @foreach($components as $key => $label)
                <div class="summary-item">
                    <div class="summary-label">{{ $label }}</div>
                    <div class="summary-value">{{ $summary[$key] }}%</div>
                </div>
                @endforeach
            </div>

            <!-- Overall Progress -->
            <div class="text-center mt-15 border-t pt-8">
                <div class="summary-label">NILAI RATA-RATA PROGRAM</div>
                <div class="final-score-value">{{ $summary['final'] }}%</div>
                <div style="width: 180px; height: 6px; background: #eeeeee; margin: 8px auto; border-radius: 3px;">
                    <div style="height: 100%; background: #333333; border-radius: 3px; width: {{ $summary['final'] }}%;"></div>
                </div>
            </div>
        </div>

        <!-- Detail Progress Per Kelas -->
        <h3 class="text-center font-bold mb-8" style="font-size: 11pt; border-bottom: 1px solid #ccc; padding-bottom: 4px;">
            DETAIL PROGRESS PER KELAS
        </h3>

        @foreach($kelasDetails as $index => $detail)
        <div class="class-section avoid-break {{ $index > 0 ? 'page-break' : '' }}">
            <!-- Class Header -->
            <div class="class-header">
                {{ $detail['kelas']->title }} - Kelas {{ $index + 1 }} dari {{ $kelasCount }}
            </div>

            <!-- Components -->
            @foreach($detail['components'] as $componentName => $component)
            @if($component['score'] > 0 || !empty($component['details_full']))
            <div class="mb-8">
                <h4 style="font-size: 9pt; font-weight: bold; margin: 8px 0 4px 0;">
                    {{ $componentName }} - Pencapaian: {{ $component['score'] }}%
                </h4>

                <table class="component-table">
                    <thead>
                        <tr>
                            <th style="width: 70%;">Item</th>
                            <th style="width: 30%;">Status/Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($component['details_full'] as $item)
                        <tr>
                            <td>{{ $item['title'] }}</td>
                            <td class="text-center">
                                @if(isset($item['score']))
                                <strong>{{ $item['score'] }}%</strong>
                                @elseif(isset($item['status']))
                                {{ $item['status'] }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
            @endforeach

            <!-- Class Final Score -->
            <div class="final-score">
                <div style="font-size: 10pt; font-weight: bold;">NILAI AKHIR KELAS</div>
                <div class="final-score-value">{{ $detail['final_score'] }}%</div>
            </div>
        </div>
        @endforeach

        <!-- Final Summary -->
        <div class="border p-8 mt-15 avoid-break">
            <h3 class="text-center font-bold mb-8" style="font-size: 11pt;">HASIL AKHIR PROGRAM</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; align-items: center;">
                <div class="text-center">
                    <div style="font-size: 10pt; margin-bottom: 4px;">Nilai Rata-rata</div>
                    <div class="final-score-value">{{ $summary['final'] }}%</div>
                </div>

                <div class="text-center">
                    <div style="font-size: 10pt; margin-bottom: 4px;">Status Kelulusan</div>
                    @php
                        $status = $summary['final'] >= 75 ? 'Lulus' : ($summary['final'] >= 60 ? 'Memenuhi Syarat' : 'Tidak Lulus');
                        $statusClass = $summary['final'] >= 75 ? 'status-lulus' :
                                     ($summary['final'] >= 60 ? 'status-memenuhi' : 'status-tidak-lulus');
                    @endphp
                    <div class="{{ $statusClass }}" style="font-size: 12pt;">
                        {{ $status }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t mt-15 pt-8">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; text-align: center;">
                <div>
                    <div style="font-weight: bold; margin-bottom: 25px; font-size: 10pt;">PESERTA</div>
                    <div style="border-bottom: 1px solid #000; width: 120px; margin: 0 auto 4px auto;"></div>
                    <div style="font-size: 9pt;">{{ $user->name }}</div>
                </div>

                <div>
                    <div style="font-weight: bold; margin-bottom: 25px; font-size: 10pt;">SISTEM PEMBELAJARAN</div>
                    <div style="border-bottom: 1px solid #000; width: 120px; margin: 0 auto 4px auto;"></div>
                    <div style="font-size: 9pt;">{{ config('app.name') }}</div>
                </div>
            </div>

            <div class="text-center mt-8" style="font-size: 8pt; color: #666;">
                <p>Dokumen ini diterbitkan secara elektronik dan memiliki kekuatan hukum yang sama dengan dokumen tercetak</p>
                <p>© {{ now()->year }} {{ config('app.name') }} - All Rights Reserved</p>
                <p>Dokumen ID: LPP-{{ $program->id }}-{{ $user->id }}-{{ now()->format('YmdHis') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
