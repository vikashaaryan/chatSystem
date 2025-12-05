{{-- this is chat list page  --}}
<div class="w-full h-full flex flex-col text-gray-200">

    <!-- Header -->
    <div class="p-4 flex justify-between items-center">
        <h2 class="text-2xl font-semibold">Chats</h2>
        <span class="cursor-pointer hover:text-blue-400 transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor">
                <path
                    d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5" />
            </svg>
        </span>
    </div>
    <hr class="border-gray-700 mx-2 mb-4">

    <!-- Filter -->
    <div x-data="{ type: 'all', query = @entangle('query') }" x-init="setTimeout(() => {
    
        conversationElement = document.getElementById('conversation-' + query);
        // scroll to the element
    
        if (conversationElement) {
            conversationElement.scrollIntoView({ 'behavior': 'smooth' })
        }
    
    }), 200;" class="flex gap-2 ml-6 mb-4">
        <button @click="type='all'" class="px-4 py-2 rounded-full text-xs font-medium border border-gray-700 transition"
            :class="type === 'all' ? 'bg-blue-600 text-white border-blue-600' : 'hover:bg-gray-800'">
            All
        </button>
        <button @click="type='deleted'"
            class="px-4 py-2 rounded-full text-xs font-medium border border-gray-700 transition"
            :class="type === 'deleted' ? 'bg-blue-600 text-white border-blue-600' : 'hover:bg-gray-800'">
            Deleted
        </button>
    </div>

    <!-- Chat List -->
    <main class="overflow-y-scroll grow px-2 pb-4 space-y-2">
        @if ($conversations)

            @foreach ($conversations as $conversation)
                <li id="conversation-{{ $conversation->id }}" wire:key="{{ $conversation->id }}"
                    class="py-3 bg-gray-800 rounded-2xl flex gap-4 cursor-pointer hover:bg-gray-700 transition relative {{ $conversation->id == $selectedConversation?->id ? 'bg-gray-700' : '' }}">
                    <!-- Clickable image -->
                    <a href="{{ route('chat', $conversation->id) }}" class="flex-shrink-0">
                        <img src="https://picsum.photos/100/50" class="w-12 h-12 rounded-full object-cover" />
                    </a>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center">
                            <!-- Clickable name -->
                            <a href="{{ route('chat', $conversation->id) }}" class="flex-1 min-w-0 mr-2">
                                <h6 class="truncate font-medium tracking-wider text-gray-200">
                                    {{ $conversation->GetReceiver()->name }}
                                </h6>
                            </a>

                            <div class="flex items-center gap-2">
                                <!-- Clickable timestamp -->
                                <a href="{{ route('chat', $conversation->id) }}"
                                    class="text-gray-400 hover:text-gray-200">
                                    <small>{{ $conversation->messages?->last()?->created_at?->shortAbsoluteDiffForHumans() }}
                                        ago</small>
                                </a>

                                <!-- Dropdown - NOT clickable for navigation -->
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="hover:text-blue-500 transition focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-three-dots-vertical">
                                                <path
                                                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                            </svg>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <div class="bg-gray-900 text-gray-200 shadow-xl rounded-md py-1">
                                            <button type="button"
                                                class="flex items-center gap-3 w-full px-4 py-2 text-sm hover:bg-gray-700 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-person-circle">
                                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                                    <path fill-rule="evenodd"
                                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                                </svg>
                                                View Profile
                                            </button>
                                            <button onclick="confirm('Are you Show you Want To Delete The Chat')" wire:click="deleteByUser('{{ encrypt($conversation->id) }}')" type="button"
                                                class="flex items-center gap-3 w-full px-4 py-2 text-sm hover:bg-gray-700 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                    <path
                                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>

                        <!-- Clickable message preview -->
                        <a href="{{ route('chat', $conversation->id) }}" class="block">
                            <div class="flex gap-x-2 items-center mt-1">
                                <p class="truncate text-sm font-light grow text-gray-400">
                                    @if ($conversation->messages?->last())
                                        <span class="flex gap-2 items-center">
                                            @if ($conversation->messages?->last()?->sender_id == auth()->id())
                                                @if ($conversation->isLastMessageReadByUser())
                                                    <svg class="text-blue-500" xmlns="http://www.w3.org/2000/svg"
                                                        width="20" height="20" fill="currentColor"
                                                        class="bi bi-check-all" viewBox="0 0 16 16">
                                                        <path
                                                            d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992zm-.92 5.14.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486z" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="20" fill="currentColor"
                                                        class="bi bi-check text-gray-300" viewBox="0 0 16 16">
                                                        <path
                                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                                    </svg>
                                                @endif
                                            @endif
                                            {{ Str::limit($conversation->messages->last()->body, 40) }}
                                        </span>
                                    @else
                                        Start a conversation...
                                    @endif
                                </p>

                                @if ($conversation->unreadMessageCount() > 0)
                                    <span
                                        class="font-bold px-2 py-1 text-xs rounded-full bg-blue-600 text-white min-w-[20px] h-5 flex items-center justify-center">
                                        {{ $conversation->unreadMessageCount() }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    </div>
                </li>
            @endforeach
        @else
        @endif

    </main>


</div>
