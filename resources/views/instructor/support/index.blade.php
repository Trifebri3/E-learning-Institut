@extends('instructor.layouts.app')

@section('title', 'Manajemen Tiket Bantuan')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Tiket Bantuan Program</h1>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-ticket-alt text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Tiket</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $counts['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Belum Dibalas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $counts['open'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <i class="fas fa-cog text-orange-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Dalam Proses</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $counts['process'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Selesai</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $counts['closed'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold mb-4">Filter Tiket</h2>

            <!-- Filter Status -->
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="{{ route('instructor.support.index', ['program_id' => request('program_id'), 'priority' => request('priority')]) }}"
                   class="px-4 py-2 rounded-lg {{ !request('status') ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700' }}">
                    Semua ({{ $counts['total'] }})
                </a>
                <a href="{{ route('instructor.support.index', ['status' => 'open', 'program_id' => request('program_id'), 'priority' => request('priority')]) }}"
                   class="px-4 py-2 rounded-lg {{ request('status') == 'open' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700' }}">
                    Belum Dibalas ({{ $counts['open'] }})
                </a>
                <a href="{{ route('instructor.support.index', ['status' => 'in_progress', 'program_id' => request('program_id'), 'priority' => request('priority')]) }}"
                   class="px-4 py-2 rounded-lg {{ request('status') == 'in_progress' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-700' }}">
                    Dalam Proses ({{ $counts['process'] }})
                </a>
                <a href="{{ route('instructor.support.index', ['status' => 'resolved', 'program_id' => request('program_id'), 'priority' => request('priority')]) }}"
                   class="px-4 py-2 rounded-lg {{ request('status') == 'resolved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                    Selesai ({{ $counts['closed'] }})
                </a>
            </div>

            <!-- Filter Program & Prioritas -->
            <form method="GET" action="{{ route('instructor.support.index') }}" class="flex flex-wrap gap-4 items-end">
                <!-- Filter Program -->
                @if($programs->count() > 1)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Program</label>
                    <select name="program_id" class="rounded-lg border-gray-300">
                        <option value="">Semua Program</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}" {{ request('program_id') == $program->id ? 'selected' : '' }}>
                                {{ $program->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Filter Prioritas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                    <select name="priority" class="rounded-lg border-gray-300">
                        <option value="">Semua Prioritas</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                    </select>
                </div>

                <!-- Hidden status filter -->
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>

                @if(request()->anyFilled(['status', 'program_id', 'priority']))
                    <a href="{{ route('instructor.support.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Tickets List -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold">Daftar Tiket</h2>
            <span class="text-sm text-gray-500">
                Menampilkan {{ $tickets->count() }} dari {{ $counts['total'] }} tiket
            </span>
        </div>

        <div class="overflow-x-auto">
            @if($tickets->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tickets as $ticket)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 font-semibold">{{ substr($ticket->user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $ticket->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $ticket->program->title ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($ticket->subject, 50) }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($ticket->description, 70) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $ticket->category == 'academic' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $ticket->category == 'permission' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                        {{ $ticket->category == 'system' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $ticket->category == 'general' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ $ticket->getCategoryLabelAttribute() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $ticket->priority == 'high' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $ticket->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $ticket->priority == 'low' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ $ticket->priority == 'high' ? 'Tinggi' : ($ticket->priority == 'medium' ? 'Sedang' : 'Rendah') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $ticket->status == 'open' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $ticket->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $ticket->status == 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $ticket->status == 'closed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ $ticket->status == 'open' ? 'Belum Dibalas' :
                                           ($ticket->status == 'in_progress' ? 'Dalam Proses' :
                                           ($ticket->status == 'resolved' ? 'Selesai' : 'Ditutup')) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $ticket->created_at->format('d M Y') }}<br>
                                    <span class="text-gray-400">{{ $ticket->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('instructor.support.show', $ticket->id) }}"
                                       class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    @if($ticket->attachment_path)
                                        <a href="{{ Storage::url($ticket->attachment_path) }}"
                                           target="_blank"
                                           class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-paperclip"></i> File
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t">
                    {{ $tickets->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500">Belum ada tiket bantuan untuk program Anda.</p>
                    @if(request()->anyFilled(['status', 'program_id', 'priority']))
                        <a href="{{ route('instructor.support.index') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                            Lihat semua tiket
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    table {
        min-width: 1000px;
    }
</style>
@endsection
