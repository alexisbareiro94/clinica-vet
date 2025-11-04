<div>
    <!-- Sidebar -->
    <div class="">
        @include('includes.sidebar-add-dueno')
    </div>

    <main class="ml-0 md:ml-64 md:pl-20 md:pt-2 pt-16 pl-2 pr-4">
        <p class="pl-1 py-7 text-4xl font-semibold">Gestion de Mascotas</p>
        <div class="mb-4 border border-gray-100 rounded-lg">
            <div class="bg-gray-200 rounded-lg ">
                <div class="p-4">
                    <button wire:click='openModalAdd'
                        class="p-2 border text-white border-gray-900 rounded-lg bg-gray-800 cursor-pointer font-semibold hover:bg-black">
                        Agregar Mascota <span class="">+</span>
                    </button>
                    <span wire:click='openModalEspecies'
                        class="p-2 border border-gray-700 text-gray-900 rounded-lg bg-gray-200 cursor-pointer font-semibold hover:bg-gray-300 hover:font-bold">
                        Agregar Especie +
                    </span>
                </div>
                <div class="p-3">
                    <!-- Buscador -->
                    <form wire:submit.prevent='filtrar'
                        class=" relative h-12 flex items-center gap-2 bg-gray-100 p-2 rounded-md w-full md:w-1/3 border border-gray-300">
                        <!-- Input de búsqueda -->
                        <input wire:model='search' wire:keydown.debounce.300ms='filtrar' type="text"
                            class="w-full bg-transparent text-sm px-3 py-2 outline-none focus:ring-2 focus:ring-gray-400 rounded-sm"
                            placeholder="Buscar por nombre">

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
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            <!-- Tabla -->
            <div class="mt-2 rounded-lg overflow-hidden shadow-md ">
                <table class="min-w-full bg-white rounded-lg hidden md:table">
                    <thead class="bg-gray-200 text-gray-800 border-t border-gray-300">
                        <tr>
                            <th class="py-3 px-4 text-left text-semibold sr-only">foto</th>
                            <th class="py-3 px-4 text-left text-semibold">Nombre</th>
                            <th class="py-3 px-4 text-left text-semibold">Especie</th>
                            <th class="py-3 px-4 text-left text-semibold">Raza</th>
                            <th class="py-3 px-4 text-left text-semibold">Nacimiento</th>
                            <th class="py-3 px-4 text-left text-semibold">Dueño</th>
                            <th class="py-3 px-4 text-center  text-semibold">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-800">
                        @foreach ($mascotas as $mascota)
                            <tr wire:key='{{ $mascota->id }}'
                                class="border-t border-gray-200 hover:bg-gray-100 transition duration-300">
                                <td class="py-3 px-4">
                                    <img class="w-12 h-12 rounded-full"
                                        src="{{ asset("uploads/mascotas/$mascota->foto") }}" alt="">
                                </td>
                                <td class="py-3 px-4">{{ $mascota->nombre }} ({{ $mascota->genero }})</td>
                                <td class="py-3 px-4"> {{ $mascota->especieN->nombre }} </td>
                                <td class="py-3 px-4"> {{ $mascota->raza }} </td>
                                <td class="py-3 px-4"> {{ App\Helpers\Helper::formatearFecha($mascota->nacimiento) }}
                                </td>
                                <td class="py-3 px-4"> {{ $mascota->dueno->nombre }} </td>
                                <td class="py-3 px-4 gap-2 flex font-semibold">
                                    <button wire:click="openModalEdit({{ $mascota->id }})"
                                        class="cursor-pointer text-gray-800 bg-gray-200 hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 rounded-md px-3 py-1 text-sm">
                                        Editar
                                    </button>
                                    <a href="{{ route('historial.completo', ['id' => $mascota->id]) }}"
                                        class="cursor-pointer text-gray-800 bg-gray-200 hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 rounded-md px-3 py-1 text-sm">
                                        Ver Consultas
                                    </a>
                                    <button wire:click="tarjetaTrue({{ $mascota->id }})"
                                        class="cursor-pointer rounded-md px-3 py-1 text-sm
                                        @if ($mascota->ultimaVacuna && $mascota->ultimaVacuna->proxima_vacunacion &&
                                            $mascota->ultimaVacuna->proxima_vacunacion <=  now()->format('Y-m-d')) 
                                            {{-- si la condicion se cumple:   --}}
                                            border border-red-500 text-red-500 bg-red-200 hover:bg-red-200 focus:ring-2 hover:text-red-700 focus:ring-red-500
                                        @else
                                            border border-gray-800 hover:bg-gray-200
                                        @endif">
                                        Tarjeta
                                    </button>


                                    <button wire:click='openModalEliminar({{ $mascota->id }})' type="button"
                                        class="ml-2 text-white bg-gray-800 hover:bg-black focus:ring-2 focus:ring-black rounded-md px-3 py-1 text-sm">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Versión móvil -->
            @include('includes.formMascotas.version-mobil')
        </div>
        <!-- modal para agregar mascota -->
        @if ($modalAdd)
            @include('includes.formMascotas.modalAdd')
        @endif
        <!-- modal para agregar especie -->
        @if ($modalEspecies)
            @include('includes.formMascotas.modalEspecies')
        @endif
        <!-- modal para editar mascota -->
        @if ($modalEliminar)
            @include('includes.formMascotas.modalEliminar')
        @endif
        <!-- modal alerta de eliminacion -->
        @if ($modalEdit)
            @include('includes.formMascotas.modalEdit')
        @endif
        @if ($buscarDueno)
            @include('includes.formMascotas.duenos')
        @endif
        @if ($modalDueno)
            @include('includes.formMascotas.add-dueno')
        @endif
        @if ($tarjeta)
            @include('includes.formMascotas.tarjeta')
        @endif
    </main>
</div>
