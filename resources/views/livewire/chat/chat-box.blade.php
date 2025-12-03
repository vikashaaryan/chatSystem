<div x-data="{
    scrollToBottom() {
        const container = document.getElementById('conversation');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }
}" x-init="// Scroll to bottom on initial load
$nextTick(() => {
    scrollToBottom();
});

// Listen for Livewire scroll-bottom event
$wire.on('scroll-bottom', () => {
    $nextTick(() => {
        scrollToBottom();
    });
});" class="w-full h-full flex flex-col bg-gray-900 text-white">

    {{-- header --}}
    <header class="shrink-0 sticky top-0 z-10 border-b border-gray-700 bg-gray-900">
        <div class="flex w-full items-center px-4 py-3 gap-3">
            <a class="shrink-0 lg:hidden" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                </svg>
            </a>

            {{-- avatar --}}
            <div class="shrink-0">
                <x-avatar class="h-10 w-10 lg:w-11 lg:h-11" />
            </div>

            <div class="min-w-0 flex-1">
                <h6 class="font-bold truncate text-base lg:text-lg">
                    {{ $selectedConversation->GetReceiver()->email }}
                </h6>
            </div>
        </div>
    </header>

    {{-- Main conversation area with scroll --}}
    <main id="conversation" class="flex-1 overflow-y-auto overscroll-contain p-3 md:p-4 space-y-3 min-h-0">
        @if ($loadedMessages && $loadedMessages->count() > 0)
            @php
                $previousMessage = null;
            @endphp
            
            @foreach ($loadedMessages as $index => $message)
                @php
                    $showAvatar = true;
                    // Hide avatar if previous message was from same sender
                    if ($previousMessage && $previousMessage->sender_id === $message->sender_id) {
                        $showAvatar = false;
                    }
                    $previousMessage = $message;
                @endphp
                
                <div @class([
                    'flex gap-2 md:gap-3 max-w-[90%]',
                    'ml-auto justify-end' => $message->sender_id === auth()->id(),
                ])>
                    {{-- Avatar for received messages only --}}
                    @if ($message->sender_id !== auth()->id())
                        <div @class([
                            'shrink-0',
                            'invisible' => !$showAvatar
                        ])>
                            <x-avatar class="w-8 h-8 md:w-9 md:h-9" />
                        </div>
                    @endif

                    {{-- Message body --}}
                    <div @class([
                        'flex flex-col max-w-full',
                        'items-end' => $message->sender_id === auth()->id(),
                    ])>
                        <div @class([
                            'rounded-2xl px-3 py-2 max-w-full',
                            'bg-gray-800 rounded-bl-none border border-gray-700' =>
                                $message->sender_id !== auth()->id(),
                            'bg-blue-600 rounded-br-none' => $message->sender_id === auth()->id(),
                        ])>
                            <p class="text-sm md:text-base break-words whitespace-normal">
                                {{ $message->body }}
                            </p>
                        </div>

                        {{-- Timestamp and status --}}
                        <div @class([
                            'flex gap-2 items-center mt-1',
                            'ml-1' => $message->sender_id !== auth()->id(),
                            'mr-1' => $message->sender_id === auth()->id(),
                        ])>
                            <p class="text-xs text-gray-400">
                                {{ $message->created_at->format('g:i a') }}
                            </p>

                            {{-- Read status for sent messages --}}
                            @if ($message->sender_id === auth()->id())
                                @if ($message->isRead())
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-check-all" viewBox="0 0 16 16">
                                        <path
                                            d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992zm-.92 5.14.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486z" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        fill="currentColor" class="bi bi-check text-gray-300" viewBox="0 0 16 16">
                                        <path
                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                    </svg>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            {{-- Empty state --}}
            <div class="h-full flex items-center justify-center">
                <p class="text-gray-500">No messages yet</p>
            </div>
        @endif

        {{-- Auto-scroll anchor --}}
        <div id="scroll-anchor"></div>
    </main>

    {{-- send message --}}
    <footer class="shrink-0 border-t border-gray-700 bg-gray-900">
        <div class="p-3 md:p-4">
            <form wire:submit.prevent="sendMessage">
                @csrf
                <div class="flex gap-2 md:gap-3 w-full">
                    <input wire:model="body" type="text" placeholder="Type your message..." maxlength="1700"
                        class="flex-1 bg-gray-800 border-0 rounded-xl px-4 py-3 text-sm md:text-base text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                    <button type="submit"
                        class="shrink-0 bg-blue-600 hover:bg-blue-700 rounded-xl px-4 md:px-6 py-3 transition-colors duration-200 flex items-center justify-center text-white font-medium">
                        <span class="hidden md:inline">Send</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 md:hidden">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                        </svg>
                    </button>
                </div>

                @error('body')
                    <p class="text-red-400 text-sm mt-2 px-1">{{ $message }}</p>
                @enderror
            </form>
        </div>
    </footer>
</div>