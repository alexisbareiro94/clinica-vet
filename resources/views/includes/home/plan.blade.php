<div class="bg-cover  bg-center rounded-2xl overflow-hidden shadow-lg transition-transform duration-300 hover:scale-105 relative"
    style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('{{ asset('images/tests/upgrade.png') }}');">

    <!-- Overlay con efecto de degradado -->
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-gray-900/50 to-gray-900/80"></div>

    <div class="relative z-10 mt-12 p-4 md:p-6">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Planes</h2>

        <div class="space-y-4 mb-8">
            <div class="flex items-center text-white/80">

                <svg class="w-6 h-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                </svg>


                <span>Mejorar mi Plan</span>
            </div>
        </div>

        <a href="{{ route('planes') }}"
            class="cursor-pointer inline-flex items-center px-6 py-3 bg-gray-300 hover:bg-gray-800 text-black hover:text-gray-100 font-medium rounded-lg transition duration-300 group">
            Acceder
            <svg class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="currentColor"
                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </a>
    </div>
</div>
