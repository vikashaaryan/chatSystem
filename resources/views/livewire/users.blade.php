<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 lg:py-16">

    <h5 class="text-center text-3xl sm:text-4xl md:text-5xl text-white font-bold py-3 sm:py-4">
        Users
    </h5>

    <!-- Responsive grid layout -->
    <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 md:gap-6 p-2 sm:p-3">
        @foreach ($users as $key => $user)
            <div class="w-full bg-[#141416] border border-gray-800 rounded-xl sm:rounded-2xl p-4 sm:p-5 md:p-6 shadow-md hover:shadow-[0_0_25px_rgba(0,132,255,0.25)] transition-all duration-300 hover:-translate-y-1">
                
                <div class="flex flex-col items-center pb-4 sm:pb-5 md:pb-6">
                    <!-- Responsive profile image -->
                    <div class="relative mb-3 sm:mb-4">
                        <img src="https://www.shutterstock.com/image-vector/avatar-gender-neutral-silhouette-vector-600nw-2470054311.jpg" 
                             class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full ring-2 ring-blue-600/40 shadow-md object-cover"
                             alt="{{ $user->name }}'s profile picture">
                    </div>

                    <!-- Responsive text sizes -->
                    <h5 class="mb-1 text-base sm:text-lg font-semibold text-gray-100 tracking-wide text-center truncate w-full px-1">
                        {{ $user->name }}
                    </h5>

                    <span class="text-xs sm:text-sm text-gray-400 text-center truncate w-full px-1">
                        {{ $user->email }}
                    </span>

                    <!-- Responsive button layout -->
                    <div class="flex flex-col xs:flex-row gap-2 sm:gap-3 w-full mt-4 sm:mt-5 md:mt-6">
                        <x-secondary-button 
                            class="!bg-gray-800 !text-gray-200 !border-gray-700 hover:!bg-gray-700 w-full xs:w-auto flex-1 justify-center text-sm sm:text-base">
                            Add Friend
                        </x-secondary-button>

                        <x-primary-button 
                            wire:click="message({{ $user->id }})"
                            class="!bg-blue-600 !text-white hover:!bg-blue-700 shadow hover:shadow-blue-900/40 transition w-full xs:w-auto flex-1 justify-center text-sm sm:text-base">
                            Message
                        </x-primary-button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>