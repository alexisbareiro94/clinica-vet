<div>
    <div class="flex justify-between">
        <div class="flex relative">
            <p class="font-semibold text-lg">Entradas</p>
            @if ($filtroTag)
                <p
                    class="font-semibold flex ml-1 pr-7 bg-gray-400 px-2 rounded-full absolute top-1 left-52 text-sm">
                    {{ $filtroTag }}
                    <span wire:click='refresh'
                        class="cursor-pointer ml-1 bg-gray-500 px-2 rounded-full absolute right-0 font-semibold hover:scale-115">
                        x
                    </span>
                </p>
            @endif            
        </div>
        <!-- boton de pdf -->
        @if (Auth::user()->plan_id != 1 and Auth::user()->plan_id != 2)
        <div>
            <button wire:click="pdfTrue" class="bg-red-600 text-white px-2 py-1 rounded-lg cursor-pointer group">
                <svg class="w-6 h-6 text-gray-800 dark:text-white transition duration-300 group-hover:rotate-12"
                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2 2 2 0 0 0 2 2h12a2 2 0 0 0 2-2 2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2V4a2 2 0 0 0-2-2h-7Zm-6 9a1 1 0 0 0-1 1v5a1 1 0 1 0 2 0v-1h.5a2.5 2.5 0 0 0 0-5H5Zm1.5 3H6v-1h.5a.5.5 0 0 1 0 1Zm4.5-3a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h1.376A2.626 2.626 0 0 0 15 15.375v-1.75A2.626 2.626 0 0 0 12.375 11H11Zm1 5v-3h.375a.626.626 0 0 1 .625.626v1.748a.625.625 0 0 1-.626.626H12Zm5-5a1 1 0 0 0-1 1v5a1 1 0 1 0 2 0v-1h1a1 1 0 1 0 0-2h-1v-1h1a1 1 0 1 0 0-2h-2Z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            </button>
        </div>
        @endif
        <!-- boton de filtro -->
        <div class="relative">
            <span class="font-semibold absolute right-11">Filtros: </span>
            <button wire:click='filtroTrue' class="cursor-pointer pr-2 bg-black rounded-md text-white px-2 py-1 group">
                <svg class=" w-6 h-6 transition duration-300 group-hover:rotate-12" xmlns="http://www.w3.org/2000/svg"
                    width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 17 17">
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
                    <th class="py-1 px-4 text-left text-semibold">Fecha</th>
                    <th class="py-1 px-4 text-left text-semibold">Cliente</th>
                    <th class="py-1 px-4 text-left text-semibold">Productos</th>
                    <th class="py-1 px-4 text-left text-semibold">Monto Gs.</th>
                </tr>
            </thead>

            <tbody class="text-gray-800">
                @foreach ($ventas as $venta)
                    <tr wire:key='{{ $venta->id }}'
                        class="border-t border-gray-200 hover:bg-gray-100 transition duration-300">
                        <td class=" px-4">
                            {{ $venta->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-4">
                            <div class="flex">
                                <p class="font-semibold mr-1">Nombre: </p>
                                <p>{{ $venta->cliente->nombre_rs }}</p>
                            </div>
                            <div class="flex">
                                <p class="font-semibold mr-1">RUC: </p>
                                <p>{{ $venta->cliente->ruc_ci }}</p>
                            </div>
                        </td>
                        <td class=" px-4">
                            @foreach ($movimientoP as $producto)
                                @if ($producto->venta_id == $venta->id)
                                    <div class="flex">
                                        <p class="font-semibold mr-1">{{ $producto->cantidad }}</p>
                                        <p>{{ $producto->producto->nombre ?? '' }}</p>
                                        <p>{{ $producto->tipoConsulta->nombre ?? '' }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </td>
                        <td class=" px-4">
                            {{ number_format($venta->monto, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if ($pdf)
        @include('includes.reportes.entradas')
    @endif

    @if ($filtro)
        @include('includes.reportes.entradas-filtro')
    @endif
</div>
