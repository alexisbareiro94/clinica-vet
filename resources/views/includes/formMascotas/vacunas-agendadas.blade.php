<div class="fixed top-0 right-0 left-0 z-20 flex justify-center items-center w-full h-full bg-black/20">
    <div class="relative p-4 w-full max-w-2xl">

        <button type="button"
            class="m-2 absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
            wire:click="vacunasAggFalse">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Cerrar</span>
        </button>

        <div class="w-full bg-white rounded-lg pt-12 pb-8 px-8">
            <p class="text-center text-2xl font-bold text-gray-800 mb-4">Vacunas Agendadas</p>
            <div>
                <table class="w-full">
                    <thead class="bg-gray-200 border-b-2 border-gray-300 ">
                        <tr>
                            <th class="py-3 px-4 text-center text-sm text-semibold">Vacuna</th>
                            <th class="py-3 px-4 text-center text-sm text-semibold">Fecha</th>
                            <th class="py-3 px-4 text-center text-sm text-semibold">Acción </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vacunasAgendadas as $vacuna)
                            <tr class="items-center ">
                                <td>{{ $vacuna->producto->nombre }}</td>
                                <td class="flex flex-col items-center mt-8 gap-2">
                                    @if ($vacuna->proxima_vacunacion <= now()->format('Y-m-d'))
                                        <span
                                            class="text-red-600 bg-red-200 hover:bg-red-200 rounded-lg font-semibold px-2 py-1">
                                            {{ $vacuna->proxima_vacunacion }}
                                        </span>
                                        <span
                                            class="text-xs text-blue-600 bg-blue-200 hover:bg-blue-200 rounded-lg font-semibold px-2 py-1">
                                            Hoy: {{ now()->format('Y-m-d') }}
                                        </span>
                                    @else
                                        {{ $vacuna->proxima_vacunacion }}
                                    @endif

                                </td>
                                <td>
                                    <div class="flex flex-col gap-1 py-4 pl-12 justify-end items-center">
                                        @if (!Auth::user()->plan_id == 1)
                                            @if ($vacuna->recordatorio)
                                                <div class="group relative">
                                                    <button
                                                        class="w-[150px] cursor-pointer text-green-700 bg-green-200 border font-semibold text-sm border-gray-200 px-2 py-1 rounded-md hover:bg-green-300">
                                                        Enviado
                                                    </button>

                                                    <button wire:click='enviarRecordatorio({{ $vacuna->id }})'
                                                        class="opacity-0 -z-10 group-hover:opacity-100 group-hover:z-10 transition-all duration-200  
                                                        absolute -top-0 right-32  w-[110px] cursor-pointer  text-gray-800 bg-gray-200 border  font-semibold text-xs border-gray-700 px-2 py-1 rounded-md hover:bg-gray-300">
                                                        Volver a Enviar
                                                    </button>
                                                </div>
                                            @else
                                                <button wire:click='enviarRecordatorio({{ $vacuna->id }})'
                                                    class="w-[150px] cursor-pointer text-gray-800 bg-gray-200 border font-semibold text-sm border-gray-200 px-2 py-1 rounded-md hover:bg-gray-300">
                                                    Enviar Recordatorio
                                                </button>
                                            @endif
                                        @endif

                                        <button
                                            wire:click='crearConsulta({{ $vacuna->producto->id }}, {{ $vacuna->id }})'
                                            class="w-[150px] cursor-pointer text-gray-800 bg-gray-200 border font-semibold text-sm border-gray-800 px-2 py-1 rounded-md hover:bg-gray-300">
                                            Crear Consulta
                                        </button>
                                        <button wire:click='deleteProximaVacunacion({{ $vacuna->id }})'
                                            class="w-[150px] border border-gray-800 font-semibold text-sm text-white px-2 py-1 rounded-md bg-gray-800 cursor-pointer hover:bg-gray-700 hover:border-gray-700">
                                            Eliminar
                                        </button>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
