@extends('participant.layouts.app')

@section('title', 'Diskusi & Pesan')

@section('content')
@php
    $isChatOpen = isset($activeForum) || isset($otherUser);
@endphp

<div class="h-[calc(100vh-5rem)] flex bg-white dark:bg-gray-900 overflow-hidden relative">

    <div class="w-full md:w-1/3 lg:w-1/4 border-r border-gray-200 dark:border-gray-800 flex flex-col bg-white dark:bg-gray-900 {{ $isChatOpen ? 'hidden md:flex' : 'flex' }}">

        <div class="p-4 border-b border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 z-10">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-3 flex items-center gap-2">
                <i class="fas fa-comments text-primary-500"></i> Diskusi
            </h2>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
                <input type="text" placeholder="Cari diskusi..." class="w-full pl-9 pr-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-colors">
            </div>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar p-2 space-y-6">

            <div>
                <h3 class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Forum Grup</h3>
                <div class="space-y-1">
                    @foreach($forums as $forum)
                        @php $isActive = isset($activeForum) && $activeForum->id == $forum->id; @endphp
                        <a href="{{ route('participant.discussion.forum', $forum->id) }}"
                           class="flex items-center gap-3 p-3 rounded-xl transition-all duration-200 group {{ $isActive ? 'bg-primary-50 dark:bg-primary-900/20' : 'hover:bg-gray-50 dark:hover:bg-gray-800' }}">

                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0 {{ $isActive ? 'bg-primary-100 text-primary-600' : 'bg-gray-100 text-gray-500 group-hover:bg-white group-hover:shadow-sm' }}">
                                <i class="fas fa-hashtag text-sm"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold {{ $isActive ? 'text-primary-700 dark:text-primary-400' : 'text-gray-700 dark:text-gray-200' }} truncate">
                                    {{ $forum->title }}
                                </h4>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ $forum->program ? $forum->program->title : 'Umum' }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pesan Pribadi</h3>
                <div class="space-y-1">
                    @foreach($contacts as $contact)
                        @php $isActive = isset($otherUser) && $otherUser->id == $contact->id; @endphp
                        <a href="{{ route('participant.discussion.dm', $contact->id) }}"
                           class="flex items-center gap-3 p-3 rounded-xl transition-all duration-200 group {{ $isActive ? 'bg-primary-50 dark:bg-primary-900/20' : 'hover:bg-gray-50 dark:hover:bg-gray-800' }}">

                            <div class="relative flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($contact->name) }}&background=random" class="w-10 h-10 rounded-full border border-gray-100 dark:border-gray-700">
                                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white dark:border-gray-900 rounded-full"></span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold {{ $isActive ? 'text-primary-700 dark:text-primary-400' : 'text-gray-700 dark:text-gray-200' }} truncate">
                                    {{ $contact->name }}
                                </h4>
                                <p class="text-xs text-gray-500 truncate">{{ ucfirst($contact->role) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="flex-1 flex flex-col bg-[#FDFDFD] dark:bg-gray-900/50 relative {{ $isChatOpen ? 'flex' : 'hidden md:flex' }}">

        @if(isset($activeForum))
            {{-- TAMPILAN FORUM --}}
            <div class="h-16 px-4 border-b border-gray-200 dark:border-gray-800 flex items-center gap-3 bg-white dark:bg-gray-900 shadow-sm z-20">
                <a href="{{ route('participant.discussion.index') }}" class="md:hidden w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-full">
                    <i class="fas fa-arrow-left"></i>
                </a>

                <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 flex-shrink-0">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white leading-tight">{{ $activeForum->title }}</h2>
                    <p class="text-xs text-gray-500 line-clamp-1">{{ $activeForum->description }}</p>
                </div>
            </div>

            <div id="chat-container" class="flex-1 overflow-y-auto p-4 space-y-6 bg-gray-50/50 dark:bg-black/20">
                @foreach($activeForum->posts as $post)
                    @php $isMe = $post->user_id == Auth::id(); @endphp
                    <div class="flex gap-3 {{ $isMe ? 'flex-row-reverse' : '' }}">
                        @if(!$isMe)
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}" class="w-8 h-8 rounded-full self-start mt-1">
                        @endif

                        <div class="max-w-[80%] md:max-w-[70%]">
                            @if(!$isMe)
                                <p class="text-[10px] font-bold text-gray-500 ml-1 mb-1">{{ $post->user->name }}</p>
                            @endif

                            <div class="px-4 py-2.5 text-sm shadow-sm
                                {{ $isMe
                                    ? 'bg-primary-600 text-white rounded-2xl rounded-tr-sm'
                                    : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-100 dark:border-gray-700 rounded-2xl rounded-tl-sm' }}">
                                {{ $post->message }}
                            </div>

                            <p class="text-[10px] text-gray-400 mt-1 {{ $isMe ? 'text-right mr-1' : 'ml-1' }}">
                                {{ $post->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
                <form action="{{ route('participant.discussion.forum', $forum->id) }}" method="POST" class="flex items-end gap-2">
                    @csrf
                    <div class="flex-1 relative">
                        <textarea name="message" rows="1" placeholder="Tulis pesan..." required
                               class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-800 border-0 rounded-2xl focus:ring-2 focus:ring-primary-500 resize-none text-sm dark:text-white"
                               style="min-height: 44px; max-height: 120px;"></textarea>
                    </div>
                    <button type="submit" class="w-11 h-11 bg-primary-600 text-white rounded-full flex items-center justify-center hover:bg-primary-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex-shrink-0">
                        <i class="fas fa-paper-plane text-sm"></i>
                    </button>
                </form>
            </div>

        @elseif(isset($otherUser))
            {{-- TAMPILAN DIRECT MESSAGE --}}
            <div class="h-16 px-4 border-b border-gray-200 dark:border-gray-800 flex items-center gap-3 bg-white dark:bg-gray-900 shadow-sm z-20">
                <a href="{{ route('participant.discussion.index') }}" class="md:hidden w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-full">
                    <i class="fas fa-arrow-left"></i>
                </a>

                <div class="relative">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($otherUser->name) }}" class="w-10 h-10 rounded-full border border-gray-100">
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white leading-tight">{{ $otherUser->name }}</h2>
                    <span class="text-xs px-2 py-0.5 bg-gray-100 dark:bg-gray-800 text-gray-500 rounded-full border border-gray-200 dark:border-gray-700">{{ ucfirst($otherUser->role) }}</span>
                </div>
            </div>

            <div id="chat-container" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50/50 dark:bg-black/20">
                @foreach($messages as $msg)
                    @php $isMe = $msg->sender_id == Auth::id(); @endphp
                    <div class="flex gap-3 {{ $isMe ? 'flex-row-reverse' : '' }}">
                        <div class="max-w-[80%] md:max-w-[70%]">
                            <div class="px-4 py-2.5 text-sm shadow-sm
                                {{ $isMe
                                    ? 'bg-primary-600 text-white rounded-2xl rounded-tr-sm'
                                    : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-100 dark:border-gray-700 rounded-2xl rounded-tl-sm' }}">
                                {{ $msg->message }}
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1 {{ $isMe ? 'text-right mr-1' : 'ml-1' }}">
                                {{ $msg->created_at->format('H:i') }}
                                @if($isMe) <i class="fas fa-check ml-1 {{ $msg->is_read ? 'text-primary-400' : 'text-gray-300' }}"></i> @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-4 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
                <form action="{{ route('participant.discussion.dm', $contact->id) }}" method="POST" class="flex items-end gap-2">
                    @csrf
                    <div class="flex-1 relative">
                        <textarea name="message" rows="1" placeholder="Tulis pesan pribadi..." required
                               class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-800 border-0 rounded-2xl focus:ring-2 focus:ring-primary-500 resize-none text-sm dark:text-white"
                               style="min-height: 44px; max-height: 120px;"></textarea>
                    </div>
                    <button type="submit" class="w-11 h-11 bg-primary-600 text-white rounded-full flex items-center justify-center hover:bg-primary-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex-shrink-0">
                        <i class="fas fa-paper-plane text-sm"></i>
                    </button>
                </form>
            </div>

        @else
            {{-- TAMPILAN KOSONG (Desktop State) --}}
            <div class="flex-1 flex flex-col items-center justify-center text-gray-300 dark:text-gray-700 p-8 text-center">
                <div class="w-24 h-24 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-6">
                    <i class="far fa-comments text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-400 dark:text-gray-500 mb-2">Mulai Diskusi</h3>
                <p class="text-sm max-w-xs mx-auto">Pilih grup forum atau teman dari daftar di sebelah kiri untuk memulai obrolan.</p>
            </div>
        @endif

    </div>
</div>

{{-- Auto Scroll Script --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var chatContainer = document.getElementById("chat-container");
        if(chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
</script>
@endsection
