@extends('adminprogram.layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <h1 class="text-2xl font-bold mb-4">Preview Laporan Submission</h1>

    <div class="bg-white shadow rounded-lg p-6">

        <table class="w-full border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">#</th>
                    <th class="border px-3 py-2">Nama Peserta</th>
                    <th class="border px-3 py-2">Nomor Induk</th>
                    <th class="border px-3 py-2">Status</th>
                    <th class="border px-3 py-2">Nilai</th>
                    <th class="border px-3 py-2">Dikirim Pada</th>
                </tr>
            </thead>

            <tbody>
                @foreach($submissions as $s)
                <tr>
                    <td class="border px-3 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-3 py-2">{{ $s->user->name }}</td>
                    <td class="border px-3 py-2">{{ $s->user->nomorInduk->nomor_induk ?? '-' }}</td>
                    <td class="border px-3 py-2">{{ $s->status }}</td>
                    <td class="border px-3 py-2">{{ $s->final_score ?? '-' }}</td>
                    <td class="border px-3 py-2">
                        @if($s->submitted_at)
                            {{ \Carbon\Carbon::parse($s->submitted_at)->format('d/m/Y H:i') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6 text-right">
            <a href="{{ route('adminprogram.essay.submissions.printAll', $exam->id) }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded">Download PDF</a>
        </div>

    </div>

</div>
@endsection
