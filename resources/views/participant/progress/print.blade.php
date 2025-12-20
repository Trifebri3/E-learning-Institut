<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Progress Program - {{ $program->title }}</title>
    <style>
        /* Reset dan Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            background: #fff;
        }

        /* Layout */
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 10px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #7c9f6f;
        }

        .header h1 {
            font-size: 16px;
            color: #2c5530;
            margin-bottom: 3px;
        }

        .header .subtitle {
            font-size: 12px;
            color: #666;
        }

        /* User Info */
        .user-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding: 8px;
            background: #f8fbf7;
            border-radius: 4px;
            border-left: 3px solid #7c9f6f;
            font-size: 9px;
        }

        .info-row {
            margin-bottom: 2px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 100px;
        }

        /* Summary Cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
            margin-bottom: 15px;
        }

        .summary-card {
            padding: 6px;
            text-align: center;
            background: #f8fbf7;
            border: 1px solid #e1ecde;
            border-radius: 4px;
        }

        .summary-score {
            font-size: 14px;
            font-weight: bold;
            color: #7c9f6f;
            margin-bottom: 1px;
        }

        .summary-label {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
        }

        /* Kelas Sections */
        .kelas-section {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .kelas-header {
            background: #7c9f6f;
            color: white;
            padding: 6px 8px;
            border-radius: 4px 4px 0 0;
            margin-bottom: 0;
        }

        .kelas-title {
            font-size: 11px;
            font-weight: bold;
        }

        .components-grid {
            border: 1px solid #e1ecde;
            border-top: none;
            border-radius: 0 0 4px 4px;
            overflow: hidden;
        }

        .component-row {
            display: flex;
            border-bottom: 1px solid #f0f7ee;
            min-height: 25px;
        }

        .component-row:last-child {
            border-bottom: none;
        }

        .component-name {
            flex: 0 0 120px;
            padding: 4px 6px;
            background: #f8fbf7;
            font-weight: bold;
            border-right: 1px solid #e1ecde;
            font-size: 9px;
            display: flex;
            align-items: center;
        }

        .component-score {
            flex: 0 0 40px;
            padding: 4px 6px;
            text-align: center;
            background: #f8fbf7;
            border-right: 1px solid #e1ecde;
            font-weight: bold;
            color: #7c9f6f;
            font-size: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .component-details {
            flex: 1;
            padding: 4px 6px;
            font-size: 8px;
        }

        .detail-item {
            margin-bottom: 2px;
            display: flex;
            justify-content: space-between;
        }

        .detail-item:last-child {
            margin-bottom: 0;
        }

        .detail-title {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .detail-value {
            font-weight: bold;
            color: #555;
            margin-left: 8px;
            flex-shrink: 0;
        }

        /* Final Score */
        .final-score {
            text-align: center;
            padding: 6px;
            background: #2c5530;
            color: white;
            border-radius: 4px;
            margin-top: 4px;
        }

        .final-score .label {
            font-size: 9px;
            opacity: 0.9;
        }

        .final-score .score {
            font-size: 12px;
            font-weight: bold;
        }

        /* Program Final */
        .program-final {
            text-align: center;
            padding: 8px;
            background: #1e3a23;
            color: white;
            border-radius: 4px;
            margin-top: 10px;
        }

        .program-final .label {
            font-size: 10px;
            opacity: 0.9;
        }

        .program-final .score {
            font-size: 14px;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e1ecde;
            text-align: center;
            color: #666;
            font-size: 8px;
        }

        /* Utility Classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mb-5 { margin-bottom: 5px; }

        /* Print Styles */
        @media print {
            body {
                font-size: 9px;
            }

            .container {
                padding: 8px;
            }

            .kelas-section {
                page-break-inside: avoid;
            }
        }

        /* Status Colors */
        .status-selesai { color: #7c9f6f; font-weight: bold; }
        .status-belum { color: #dc2626; }
        .status-hadir_full { color: #7c9f6f; font-weight: bold; }
        .status-hadir_awal { color: #d97706; }
        .status-hadir_akhir { color: #d97706; }
        .status-alfa { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>LAPORAN PROGRESS PROGRAM</h1>
            <div class="subtitle">{{ $program->title }}</div>
        </div>

        <!-- User & Program Info -->
        <div class="user-info">
            <div class="user-details">
                <div class="info-row">
                    <span class="info-label">Nama Peserta:</span>
                    {{ $user->name }}
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    {{ $user->email }}
                </div>
            </div>
            <div class="program-details">
                <div class="info-row">
                    <span class="info-label">Tanggal Cetak:</span>
                    {{ \Carbon\Carbon::now()->translatedFormat('d/m/Y H:i') }}
                </div>
                <div class="info-row">
                    <span class="info-label">Total Kelas:</span>
                    {{ $kelasCount }} kelas
                </div>
            </div>
        </div>

        <!-- Summary Overview -->
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-score">{{ $summary['assignment'] }}%</div>
                <div class="summary-label">Tugas</div>
            </div>
            <div class="summary-card">
                <div class="summary-score">{{ $summary['quiz'] }}%</div>
                <div class="summary-label">Quiz</div>
            </div>
            <div class="summary-card">
                <div class="summary-score">{{ $summary['essay'] }}%</div>
                <div class="summary-label">Esai</div>
            </div>
            <div class="summary-card">
                <div class="summary-score">{{ $summary['presensi'] }}%</div>
                <div class="summary-label">Presensi</div>
            </div>
            <div class="summary-card">
                <div class="summary-score">{{ $summary['learning'] }}%</div>
                <div class="summary-label">Learning</div>
            </div>
            <div class="summary-card">
                <div class="summary-score">{{ $summary['video'] }}%</div>
                <div class="summary-label">Video</div>
            </div>
            <div class="summary-card">
                <div class="summary-score">{{ $summary['modul'] }}%</div>
                <div class="summary-label">Modul</div>
            </div>
            <div class="summary-card">
                <div class="summary-score">{{ $summary['custom'] }}%</div>
                <div class="summary-label">Tambahan</div>
            </div>
        </div>

        <!-- Detail Per Kelas -->
        @foreach($kelasDetails as $detail)
        <div class="kelas-section">
            <div class="kelas-header">
                <div class="kelas-title">{{ $detail['kelas']->title }}</div>
            </div>

            <div class="components-grid">
                @foreach($detail['components'] as $componentName => $component)
                @if($component['score'] > 0 || !empty($component['details_full']))
                <div class="component-row">
                    <div class="component-name">{{ $componentName }}</div>
                    <div class="component-score">{{ $component['score'] }}%</div>
                    <div class="component-details">
                        @foreach($component['details_full'] as $item)
                        <div class="detail-item">
                            <span class="detail-title">{{ $item['title'] }}</span>
                            <span class="detail-value">
                                @if(isset($item['score']))
                                    {{ $item['score'] }}%
                                @elseif(isset($item['status']))
                                    @php
                                        $statusClass = 'status-' . str_replace(' ', '_', strtolower($item['status']));
                                    @endphp
                                    <span class="{{ $statusClass }}">{{ $item['status'] }}</span>
                                @endif
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @endforeach
            </div>

            <!-- Final Score Kelas -->
            <div class="final-score">
                <div class="label">NILAI AKHIR KELAS</div>
                <div class="score">{{ $detail['final_score'] }}%</div>
            </div>
        </div>
        @endforeach

        <!-- Program Final Summary -->
        <div class="program-final">
            <div class="label">NILAI RATA-RATA PROGRAM</div>
            <div class="score">{{ $summary['final'] }}%</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Dokumen ini dicetak secara otomatis dari Sistem Pembelajaran<br>
            {{ config('app.name') }} - {{ \Carbon\Carbon::now()->year }}
        </div>
    </div>
</body>
</html>
