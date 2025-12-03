<div class="max-w-6xl mx-auto my-16">

    <h5 class="text-center text-5xl text-white font-bold py-3">Users</h5>



    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 p-2 ">

        @foreach ($users as $key=> $user)
            


       <div class="w-full bg-[#141416] border border-gray-800 rounded-2xl p-6 shadow-md hover:shadow-[0_0_25px_rgba(0,132,255,0.25)] transition-all duration-300 hover:-translate-y-1">

    <div class="flex flex-col items-center pb-6">

        <img src="https://www.shutterstock.com/image-vector/avatar-gender-neutral-silhouette-vector-600nw-2470054311.jpg" 
        class="w-24 h-24 mb-3 rounded-full ring-2 ring-blue-600/40 shadow-md">

        <h5 class="mb-1 text-lg font-semibold text-gray-100 tracking-wide">
            {{$user->name}}
        </h5>

        <span class="text-sm text-gray-400">
            {{$user->email}}
        </span>

        <div class="flex mt-5 space-x-3 md:mt-6">

            <x-secondary-button class="!bg-gray-800 !text-gray-200 !border-gray-700 hover:!bg-gray-700">
                Add Friend
            </x-secondary-button>

            <x-primary-button wire:click="message({{$user->id}})" 
                class="!bg-blue-600 !text-white hover:!bg-blue-700 shadow hover:shadow-blue-900/40 transition">
                Message
            </x-primary-button>

        </div>

    </div>

</div>


        @endforeach
    </div>




</div>
