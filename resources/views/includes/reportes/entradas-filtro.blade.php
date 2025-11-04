<div id="confirmarModal" tabindex="-1"
    class="fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-full bg-black/50">
    <div class="relative p-4 w-full max-w-md">

        <button type="button"
            class="m-2 absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
            wire:click="filtroFalse">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Cerrar</span>
        </button>
        {{--  --}}
        <div class="bg-white border border-gray-100 p-4 max-w-md mx-auto shadow-lg rounded-lg">
            <p class="text-2xl font-semibold text-center text-gray-800 mb-6">Opciones de filtro</p>
            <p class="text-sm text-gray-600 mb-4 border-l-3 p-3 border-green-500 bg-green-200 rounded-md">
                La exportación de reportes está disponible a partir del plan Estándar.
            </p>

            <div class="mb-5">
                <p class="font-medium text-gray-700">Por búsqueda</p>
                <form wire:submit.prevent='filtrar'
                    class="relative h-10 flex items-center gap-2 bg-gray-100 p-2 rounded-md w-full  border border-gray-300">
                    <!-- Input de búsqueda -->
                    <input wire:model='search' type="text"
                        class="w-full bg-transparent text-sm px-3 py-1 outline-none focus:ring-2 focus:ring-gray-400 rounded-sm"
                        placeholder="Buscar producto">

                    <!-- Botón para limpiar el input -->
                    @if ($search)
                        <button type="button" wire:click="flag"
                            class="px-1.5 py-0.5 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-200  transition">
                            ✕
                        </button>
                    @endif
                    <!-- Botón de búsqueda -->

                    <button type="submit" class="bg-gray-200 hover:bg-gray-300 transition p-2 rounded-lg">
                        <svg class="w-5 h-5 text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 -3 21 21">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </button>
                </form>

                <form wire:submit.prevent='filtrar'>
                    <div class="flex flex-col">
                        <p class="font-medium text-gray-700">Por Fecha</p>
                        <label for="">desde</label>
                        <input type="date" wire:model="desde" id="fecha" class="bg-gray-200 rounded-md p-2 mb-2">

                        <label for="">hasta</label>
                        <input type="date" wire:model='hasta' class="bg-gray-200 rounded-md p-2 mb-2">
                    </div>
                    <button type="submit"
                        class="w-full bg-gray-800 text-white font-medium py-2 rounded-md hover:bg-black transition duration-300">
                        Aceptar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
