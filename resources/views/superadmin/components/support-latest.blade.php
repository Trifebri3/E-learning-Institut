<div class="bg-white shadow rounded-lg p-4">
    <h3 class="font-semibold text-lg mb-3">Tiket Terbaru (Belum Direspon)</h3>

    @if($tickets->isEmpty())
        <p class="text-gray-500 text-sm">Tidak ada tiket terbaru.</p>
    @else
        <div class="space-y-3">
            @foreach($tickets as $t)
                <a href="{{ route('superadmin.support.show', $t->id) }}"
                   class="flex items-start justify-between p-3 border rounded-lg hover:bg-gray-50 transition">

                    <div>
                        <p class="font-medium text-gray-900">
                            {{ $t->title }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Dari: {{ $t->user->name }} • {{ $t->created_at->diffForHumans() }}
                        </p>

                        <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-600">
                            {{ ucfirst(str_replace('_', ' ', $t->status)) }}
                        </span>
                    </div>

                </a>
            @endforeach
        </div>
    @endif
</div>
