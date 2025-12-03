<div class="text-white border border-white w-full h-full shadow-md fixed inset-0 lg:top-16 lg:inset-x-2 m-auto lg:h-[90%] rounded-t-lg overflow-hidden">
    <div class="flex h-full">
        <div class="w-4/12 border-r border-white">
            <livewire:chat.chat-list :selectedConversation="$selectedConversation" :query="$query">
        </div>
        <div class="w-8/12 h-full relative">
            <livewire:chat.chat-box :selectedConversation="$selectedConversation">
        </div>
    </div>
</div>