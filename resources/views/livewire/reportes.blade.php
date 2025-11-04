<div>
    <div>
        @include('includes.sidebar-add-dueno')
    </div>

    <main class="ml-0 md:ml-64 md:pl-20 md:pt-2 pt-16 pl-2 pr-4">
        <p class="pl-1 py-7 text-4xl font-semibold">Reportes</p>

        @if (Auth::user()->plan_id == 1 || Auth::user()->plan_id == 2)
            <div class="grid grid-cols-2 gap-4 px-12">
        @else
            <div class="grid grid-cols-3 gap-4 px-12">
        @endif
        @if (Auth::user()->plan_id == 1 || Auth::user()->plan_id == 2)
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md">
                <p class="font-medium">Funcionalidad disponible desde el plan estándar</p>
                <p class="text-sm mt-1">
                    Para ver los <span class="font-semibold">productos más vendidos</span> y las <span
                        class="font-semibold">consultas más realizadas</span>, necesitas un plan de pago.
                </p>

                <a href="#"
                    class="inline-block mt-3 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded transition">
                    Mejorar mi plan
                </a>
            </div>
        @else
            <div class="col-span-2 bg-gray-100 rounded-lg p-4 ">
                <div class="flex justify-between">
                    <div class="flex relative">
                        <p class="font-semibold text-lg">
                            @if ($filtroPor == 1)
                                Productos mas vendidos
                            @else
                                Consultas mas realizadas
                            @endif
                        </p>
                        @if ($filtroTag)
                            <p
                                class="font-semibold flex ml-1 pr-7 bg-gray-400 px-2 rounded-full absolute top-1 left-52 text-sm">
                                {{ $filtroTag }}
                                <span wire:click='refresh'
                                    class="cursor-pointer ml-1 bg-gray-500 px-2 rounded-full absolute right-0 font-semibold hover:scale-115 ">
                                    x
                                </span>
                            </p>
                        @endif
                        {{-- <p class="bg-gray-400 p-1">{{ $filtroTag }}</p> --}}
                    </div>
                    <!-- boton de pdf -->
                    <div>
                        <button wire:click="fechasTrue"
                            class="bg-red-600 text-white px-2 py-1 rounded-lg cursor-pointer group">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white transition duration-300 group-hover:rotate-12"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2 2 2 0 0 0 2 2h12a2 2 0 0 0 2-2 2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2V4a2 2 0 0 0-2-2h-7Zm-6 9a1 1 0 0 0-1 1v5a1 1 0 1 0 2 0v-1h.5a2.5 2.5 0 0 0 0-5H5Zm1.5 3H6v-1h.5a.5.5 0 0 1 0 1Zm4.5-3a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h1.376A2.626 2.626 0 0 0 15 15.375v-1.75A2.626 2.626 0 0 0 12.375 11H11Zm1 5v-3h.375a.626.626 0 0 1 .625.626v1.748a.625.625 0 0 1-.626.626H12Zm5-5a1 1 0 0 0-1 1v5a1 1 0 1 0 2 0v-1h1a1 1 0 1 0 0-2h-1v-1h1a1 1 0 1 0 0-2h-2Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        </button>
                    </div>
                    <!-- boton de filtro -->
                    <div class="relative">
                        <span class="font-semibold absolute right-11">Filtros: </span>
                        <button wire:click='filtroTrue'
                            class="cursor-pointer pr-2 bg-black rounded-md text-white px-2 py-1 group">
                            <svg class=" w-6 h-6 transition duration-300 group-hover:rotate-12"
                                xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-funnel-fill" viewBox="0 0 17 17">
                                <path
                                    d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mt-2 rounded-lg overflow-hidden shadow-md ">
                    <table class="min-w-full bg-white rounded-lg hidden md:table">
                        <thead class="bg-gray-200 text-gray-800 border-t border-gray-300">
                            <tr>
                                <th class="py-1 px-4 text-left text-semibold sr-only">foto</th>
                                <th class="py-1 px-4 text-left text-semibold">
                                    {{ $filtroPor == 1 ? 'Producto' : 'Consulta' }}</th>
                                <th class="py-1 px-4 text-left text-semibold">
                                    {{ $filtroPor == 1 ? 'Ventas' : 'Veces realizadas' }}</th>
                            </tr>
                        </thead>

                        <tbody class="text-gray-800">
                            @foreach ($ventas as $venta)
                                <tr wire:key='{{ $venta->id }}'
                                    class="border-t border-gray-200 hover:bg-gray-100 transition duration-300">
                                    <td class="px-4 {{ $filtroPor == 2 ? 'py-2' : '' }}">
                                        @if ($venta->foto == null)
                                            <img class="w-3 h-3" src="{{ asset('images/tabicon.png') }}"
                                                alt="Foto del producto">
                                        @else
                                            <img class="w-12 h-12"
                                                src="{{ asset('uploads/productos/' . $venta->foto) }}"
                                                alt="Foto del producto">
                                        @endif
                                    </td>
                                    <td class=" px-4 {{ $filtroPor == 2 ? 'py-2' : '' }}">
                                        {{ $venta->nombre }}
                                    </td>
                                    <td class=" px-4 {{ $filtroPor == 2 ? 'py-2' : '' }}">
                                        @if ($filtroPor == 1)
                                            {{ $venta->ventas }}
                                        @else
                                            {{ $venta->veces_realizadas }}
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        <div class="bg-gray-100 h-48 rounded-lg p-4 bg-cover bg-center">
            @include('includes.reportes.razas')
        </div>


        <div class="col-span-3 bg-gray-100 rounded-lg p-4 ">
            @livewire('entradas-table')
        </div>

</div>
</main>

@if ($fechas)
    @include('includes.reportes.ventas')
@endif

@if ($filtro)
    @include('includes.reportes.filtros')
@endif
</div>
