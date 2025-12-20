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

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 10px;
        }

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
        }

        .summary-label {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
        }

        .kelas-section {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .kelas-header {
            background: #7c9f6f;
            color: white;
            padding: 6px 8px;
            border-radius: 4px 4px 0 0;
        }

        .kelas-title {
            font-size: 11px;
            font-weight: bold;
        }

        .components-grid {
            border: 1px solid #e1ecde;
            border-top: none;
            border-radius: 0 0 4px 4px;
        }

        .component-row {
            display: flex;
            border-bottom: 1px solid #f0f7ee;
            min-height: 25px;
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

        .final-score {
            text-align: center;
            padding: 6px;
            background: #2c5530;
            color: white;
            border-radius: 4px;
            margin-top: 4px;
        }

        .program-final {
            text-align: center;
            padding: 8px;
            background: #1e3a23;
            color: white;
            border-radius: 4px;
            margin-top: 10px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e1ecde;
            text-align: center;
            color: #666;
            font-size: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LAPORAN PROGRESS PROGRAM</h1>
            <div>{{ $program->title }}</div>
        </div>

        <div class="user-info">
            <div>
                <div><strong>Nama:</strong> {{ $user->name }}</div>
                <div><strong>Email:</strong> {{ $user->email }}</div>
            </div>
            <div>
                <div><strong>Tanggal:</strong> {{ now()->format('d/m/Y H:i') }}</div>
                <div><strong>Kelas:</strong> {{ $kelasCount }} kelas</div>
            </div>
        </div>

        <div class="summary-grid">
            @foreach(['assignment' => 'Tugas', 'quiz' => 'Quiz', 'essay' => 'Esai', 'presensi' => 'Presensi',
                     'learning' => 'Learning', 'video' => 'Video', 'modul' => 'Modul', 'custom' => 'Tambahan'] as $key => $label)
            <div class="summary-card">
                <div class="summary-score">{{ $summary[$key] }}%</div>
                <div class="summary-label">{{ $label }}</div>
            </div>
            @endforeach
        </div>

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
                            <span>{{ $item['title'] }}</span>
                            <span>
                                @if(isset($item['score'])){{ $item['score'] }}%
                                @elseif(isset($item['status'])){{ $item['status'] }}
                                @endif
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @endforeach
            </div>

            <div class="final-score">
                <div><strong>NILAI AKHIR: {{ $detail['final_score'] }}%</strong></div>
            </div>
        </div>
        @endforeach

        <div class="program-final">
            <div><strong>NILAI RATA-RATA PROGRAM: {{ $summary['final'] }}%</strong></div>
        </div>

        <div class="footer">
            {{ config('app.name') }} - {{ now()->year }}
        </div>
    </div>
</body>
</html>
