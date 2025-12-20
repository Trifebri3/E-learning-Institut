@extends('adminprogram.layouts.app')

@section('title', 'Diskusi Program')

@section('content')
<div class="h-[calc(100vh-5rem)] flex bg-white dark:bg-gray-900 overflow-hidden">

    <!-- SIDEBAR (Kiri) -->
    <div class="w-full md:w-1/3 lg:w-1/4 border-r border-gray-200 dark:border-gray-700 flex flex-col bg-gray-50 dark:bg-gray-800">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-yellow-600 text-white">
            <h2 class="text-xl font-bold"><i class="fas fa-comments mr-2"></i> Admin Chat</h2>
        </div>

        <div class="flex-1 overflow-y-auto p-2 space-y-6">
            <!-- Grup -->
            <div>
                <h3 class="px-3 text-xs font-bold text-gray-500 uppercase mb-2">Forum Program</h3>
                @foreach($forums as $forum)
                    <a href="{{ route('adminprogram.discussion.forum', $forum->id) }}"
                       class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition {{ isset($activeForum) && $activeForum->id == $forum->id ? 'bg-yellow-100 dark:bg-gray-700' : '' }}">
                        <div class="w-8 h-8 rounded bg-yellow-600 text-white flex items-center justify-center text-xs">#</div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $forum->title }}</h4>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Kontak User -->
            <div>
                <h3 class="px-3 text-xs font-bold text-gray-500 uppercase mb-2">Chat User</h3>
                @foreach($contacts as $contact)
                    <a href="{{ route('adminprogram.discussion.dm', $contact->id) }}"
                       class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition {{ isset($otherUser) && $otherUser->id == $contact->id ? 'bg-yellow-100 dark:bg-gray-700' : '' }}">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($contact->name) }}" class="w-8 h-8 rounded-full">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $contact->name }}</h4>

                            {{-- Badge Role Kecil --}}
                            @php
                                $roleColor = match($contact->role) {
                                    'superadmin' => 'text-red-600',
                                    'admin_program' => 'text-yellow-600',
                                    'instructor' => 'text-purple-600',
                                    default => 'text-gray-500'
                                };
                            @endphp
                            <p class="text-xs {{ $roleColor }} truncate">{{ ucfirst($contact->role) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- MAIN AREA (Kanan) -->
    <div class="flex-1 flex flex-col bg-gray-100 dark:bg-gray-900 relative">

        @if(isset($activeForum) || isset($otherUser))

            {{-- HEADER CHAT --}}
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3 bg-white dark:bg-gray-800 shadow-sm z-10">
                @if(isset($activeForum))
                    <div class="w-10 h-10 rounded bg-yellow-600 text-white flex items-center justify-center font-bold"><i class="fas fa-users"></i></div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $activeForum->title }}</h2>
                        <p class="text-xs text-gray-500">Forum Diskusi</p>
                    </div>
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($otherUser->name) }}" class="w-10 h-10 rounded-full">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $otherUser->name }}</h2>
                        <p class="text-xs text-gray-500">Role: {{ ucfirst($otherUser->role) }}</p>
                    </div>
                @endif
            </div>

            {{-- ISI CHAT --}}
            <div id="chat-container" class="flex-1 overflow-y-auto p-6 space-y-6">

                @php
                    $chats = isset($activeForum) ? $activeForum->posts : $messages;
                @endphp

        @foreach($chats as $chat)
    @php
        $isMe = ($chat->user_id ?? $chat->sender_id) == Auth::id();
        $sender = isset($activeForum) ? $chat->user : $chat->sender;

        // LOGIKA WARNA BADGE ROLE
        $badgeColor = match($sender->role) {
            'superadmin' => 'bg-red-100 text-red-800 border-red-200',
            'admin_program' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'instructor' => 'bg-purple-100 text-purple-800 border-purple-200',
            default => 'bg-gray-100 text-gray-600 border-gray-200'
        };

        // LOGIKA LABEL ROLE
        $roleLabel = match($sender->role) {
            'superadmin' => 'Super Admin',
            'admin_program' => 'Admin Program',
            'instructor' => 'Instruktur',
            'participant' => 'Peserta',
            default => 'Unknown'
        };
    @endphp


                    <div class="flex gap-4 {{ $isMe ? 'flex-row-reverse' : '' }}">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($sender->name) }}" class="w-10 h-10 rounded-full shadow-sm mt-1">

                        <div class="max-w-[70%]">

                            @if(!$isMe)
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-bold text-gray-600 dark:text-gray-300">{{ $sender->name }}</span>
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase border {{ $badgeColor }}">
                                        {{ $roleLabel }}
                                    </span>
                                </div>
                            @endif

                            <div class="px-5 py-3 text-sm shadow-sm
                                {{ $isMe
                                    ? 'bg-yellow-600 text-white rounded-2xl rounded-tr-none'
                                    : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-2xl rounded-tl-none border border-gray-100 dark:border-gray-600'
                                }}">
                                {!! nl2br(e($chat->message)) !!}
                            </div>

                            <p class="text-[10px] text-gray-400 mt-1 {{ $isMe ? 'text-right' : '' }}">
                                {{ $chat->created_at->format('d M, H:i') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- INPUT FORM --}}
            <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                <form action="{{ isset($activeForum) ? route('adminprogram.discussion.forum.store', $activeForum->id) : route('adminprogram.discussion.dm.store', $otherUser->id) }}"
                      method="POST" class="flex gap-3">
                    @csrf
                    <input type="text" name="message" placeholder="Kirim pesan sebagai Admin Program..." autocomplete="off" required
                           class="flex-1 px-5 py-3 border border-gray-300 dark:border-gray-600 rounded-full bg-gray-50 dark:bg-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-yellow-500 transition">

                    <button type="submit" class="w-12 h-12 bg-yellow-600 text-white rounded-full flex items-center justify-center hover:bg-yellow-700 shadow-lg transition transform hover:scale-105">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>

        @else
            {{-- EMPTY STATE --}}
            <div class="flex-1 flex flex-col items-center justify-center text-gray-400">
                <div class="w-20 h-20 bg-gray-200 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-comments text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-600 dark:text-gray-300">Diskusi Program</h3>
                <p class="text-sm">Pilih forum atau peserta untuk memulai percakapan.</p>
            </div>
        @endif

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var chatContainer = document.getElementById("chat-container");
        if(chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
</script>
@endsection
