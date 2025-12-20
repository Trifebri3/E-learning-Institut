<h2 style="text-align:center;">HASIL UJIAN ESSAY</h2>
<hr>

<p><strong>Nama Peserta:</strong> {{ $submission->user->name }}</p>
<p><strong>Ujian:</strong> {{ $submission->exam->title }}</p>
<p><strong>Nilai Akhir:</strong> {{ number_format($submission->final_score, 2) }}</p>

<hr>

<h3>Detail Jawaban</h3>

<table width="100%" border="1" cellspacing="0" cellpadding="6">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th>Pertanyaan</th>
            <th>Jawaban</th>
            <th width="10%">Nilai</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($submission->answers as $i => $answer)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $answer->question->question_text }}</td>
            <td>{{ $answer->answer_text }}</td>
            <td>{{ $answer->score }}</td>
            <td>{{ $answer->notes }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<hr>

<p style="text-align:right; margin-top:20px;">
    Dicetak pada: {{ now()->format('d/m/Y H:i') }}
</p>
