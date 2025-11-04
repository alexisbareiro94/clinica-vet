<div id="gestion-pacientes"
    class=" bg-cover bg-center rounded-2xl overflow-hidden shadow-lg transition-transform duration-300 hover:scale-105 relative"
    style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('{{ asset('images/tests/gestion-pacientes.webp') }}');">

    <!-- Overlay con efecto de degradado -->
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-gray-900/50 to-gray-900/80"></div>

    <div class="relative z-10 p-4 mt-12 md:p-6">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Gestión de Pacientes</h2>

        <div class="space-y-4 mb-8">
            <div class="flex items-center text-white/80">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Registro de mascotas y dueños</span>
            </div>
        </div>
        <button wire:click='abrirModal'
            class="cursor-pointer inline-flex items-center px-6 py-3 bg-gray-300 hover:bg-gray-800 text-black hover:text-gray-100 font-medium rounded-lg transition duration-300 group">
            Acceder
            <svg class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="currentColor"
                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
</div>
