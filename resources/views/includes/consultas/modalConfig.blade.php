<div id="confirmarModal" tabindex="-1"
    class=" fixed top-0 right-0 left-0 z-40 flex justify-center items-center w-full h-full bg-black/50">
    <div class="relative p-4 w-full max-w-[550px]">
        <button type="button"
            class="cursor-pointer m-2 absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
            wire:click="closeModalConfig">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Cerrar</span>
        </button>

        <div
            class="bg-white border border-gray-100 p-6 min-w-lg mx-auto shadow-lg rounded-lg max-h-[620px] outline-none overflow-x-hidden overflow-y-auto">
            <p class="text-2xl font-semibold text-center text-gray-800 mb-6">Actualizar Consulta</p>

            <!-- NOTAS -->
            @if ($consultaToEdit->notas != null)
                <div class="p-2 flex w-auto rounded-md bg-blue-100  text-blue-800 shadow-sm">
                    <div class="flex">
                        <svg class="w-6 h-6  " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <p class="pl-0.5 font-semibold ">Nota: </p> <span
                            class="pl-1">{{ $consultaToEdit->notas }}</span>
                    </div>
                </div>
            @endif

            <!-- FORMULARIO PARA CAMBIAR DE VETERINARIO -->
            <form wire:submit.prevent='updateVet'>
                <div class="shadow-lg rounded-lg p-2 bg-gray-100 mt-3 relative">
                    <div class="grid grid-cols-2 rounded-lg gap-4 mt-2 hover:bg-gray-100">
                        <!-- grid de foto -->
                        <div class="">
                            @php
                                $mascota = App\Models\Mascota::find($consultaToEdit->mascota_id);
                            @endphp
                            <img class="w-[200px] aspect-[4/3] object-cover rounded-lg"
                                src="{{ asset("uploads/mascotas/$mascota->foto") }}" alt="">
                        </div>

                        <!-- grid de la info de la consulta -->
                        <div>
                            <p class="text-sm font-bold p-2 h-auto">{{ $mascota->nombre }} <span class="text-gray-600">|
                                    {{ $mascota->dueno->nombre }}</span></p>
                            <div class="border-t border-gray-300 mt-2 pt-2 text-sm">
                                <p class="text-gray-700"><b>Historial clínico:</b> {{ $mascota->historial_medico }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Atendido por -->
                    <div class="p-2 border-gray-400">
                        <div class="p-2">
                            <p class="font-semibold">Atendido por:</p>
                            <span>{{ $consultaToEdit->veterinario->name }}</span>
                            @if (!$cambiarVet)
                                <span wire:click='openCambiarVet'
                                    class="text-xs cursor-pointer m-4 p-1 rounded-lg text-white font-semibold bg-gray-800">cambiar</span>
                            @else
                                @if (empty($vetChanged))
                                    <span wire:click='closeCambiarVet'
                                        class="text-xs cursor-pointer m-4 p-1 rounded-lg text-white font-semibold bg-gray-800">cancelar</span>
                                @else
                                    <span wire:click='closeCambiarVet'
                                        class="text-xs cursor-pointer m-4 p-1 rounded-lg text-white font-semibold bg-gray-800">cancelar</span>

                                    <button wire:click='closeCambiarVet' type="submit"
                                        class="text-xs cursor-pointer p-1 rounded-lg text-white font-semibold bg-gray-800">aceptar</button>
                                @endif
                            @endif
                            <!-- grupo de veterinarios -->
                            @php
                                $veterinariosEnConsulta = App\Models\ConsultaVeterinario::where(
                                    'consulta_id',
                                    $consultaToEdit->id,
                                )
                                    ->where('owner_id', App\Helpers\Helper::ownerId())
                                    ->pluck('veterinario_id')
                                    ->toArray();
                            @endphp

                            @foreach ($grupoVet as $vet)
                                @if (in_array($vet->veterinario_id, $veterinariosEnConsulta))
                                    <div>
                                        <p class="text-xs underline">atendido tambien por:</p>
                                        <span class="text-xs">{{ $vet->veterinario->name }}
                                            <span wire:click='eliminarVetGrupo({{ $vet->id }})'
                                                wire:confirm='Estas seguro/a de eliminar del grupo?'
                                                class="px-1.5 py-0.5 cursor-pointer bg-gray-200 rounded-md font-semibold hover:bg-red-300 hover:text-black">
                                                x
                                            </span>
                                        </span>
                                    </div>
                                @endif
                            @endforeach

                        </div>

                        <!-- SELECT DE CAMBIO DE VETERINARIO -->
                        @if ($cambiarVet)
                            <div class="mb-5">
                                <label class="block text-gray-800 font-medium mb-2">Seleccionar nuevo
                                    veterinario</label>
                                <select wire:model='cambiarVetId' wire:click='setVetChanged({{ 1 }})'
                                    class="w-1/2 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                                    <option value="">-Seleccionar-</option>
                                    @php
                                        $veterinariosEnConsulta = App\Models\ConsultaVeterinario::where(
                                            'consulta_id',
                                            $consultaToEdit->id,
                                        )
                                            ->pluck('veterinario_id')
                                            ->toArray();
                                        $veterinariosEnConsulta[] = $consultaToEdit->veterinario_id; // Agregar el principal
                                    @endphp

                                    @foreach ($veterinarios as $veterinario)
                                        @if (!in_array($veterinario->id, $veterinariosEnConsulta))
                                            <option value="{{ $veterinario->id }}">
                                                {{ $veterinario->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
            </form>

            @if (Auth::user()->plan_id == 1)
                <div x-data="{ abierto: false }" class="relative p-4 my-3 bg-blue-50 rounded-xl text-blue-800 group">
                    <h3 class="flex text-base font-semibold mb-2">
                        Función disponible desde el plan básico
                        <button @click="abierto = !abierto" class="ml-4 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                            </svg>
                        </button>
                    </h3>

                    <div x-show="abierto" x-transition class="text-sm">
                        <p>
                            Con el <span class="font-bold">plan básico</span> puedes agregar productos directamente a
                            una
                            consulta y se descuentan automáticamente del stock.
                        </p>
                        <p class="mt-1">
                            En el <span class="font-bold">plan gratuito</span>, recomendamos escribir los productos usados en la consulta manualmente
                            en
                            el campo de <span class="italic">tratamientos</span>.
                        </p>
                    </div>

                    <button
                        class="cursor-pointer font-semibold mt-2 px-3 py-1 bg-gray-300 rounded text-sm transition-all duration-500 hover:bg-gradient-to-r hover:from-green-200 hover:to-green-300 hover:text-green-600 hover:font-semibold">
                        Cambiar plan
                    </button>
                </div>
            @else
                <!-- funcionalidad de consumo en consulta -->
                <div>
                    <!-- MUESTRA LOS PRODUCTOS QUE SE CONSUMIO EN LA CONSULTA -->
                    @if (count($consultasProductos) > 0)
                        <div class="mt-4">
                            <p class="text-gray-700 font-medium text-sm mb-2">Insumos Consumido</p>
                            <div class="grid grid-cols-4 gap-1.5">
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($consultasProductos as $cproducto)
                                    <div
                                        class="relative group bg-gray-100 shadow-lg rounded-md border border-gray-100 hover:border-red-400 transition-all">
                                        <!-- Botón de eliminación -->
                                        <button type="button"
                                            wire:click='disminuirCantidad({{ $consultaToEdit->id }}, {{ $cproducto->producto_id }})'
                                            class="absolute hidden group-hover:flex items-center justify-center inset-0 bg-white/50 cursor-pointer">
                                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>

                                        <!-- Contenido del producto -->
                                        <div class="flex flex-col items-center p-2">
                                            <img class="w-12 h-12 object-cover rounded-md mb-1"
                                                src="{{ asset('uploads/productos/' . $cproducto->producto->foto) }}"
                                                alt="">
                                            <div class="text-center">
                                                <p
                                                    class="text-xs font-medium text-gray-700 leading-tight mb-0.5 line-clamp-2">
                                                    {{ $cproducto->producto->nombre }}
                                                </p>
                                                <p class="text-[10px] text-gray-500 font-medium">
                                                    {{ $cproducto->producto->precio_interno }} Gs.
                                                    {{ $cproducto->producto->unidad_medida }}
                                                </p>
                                                <p class="text-[10px] text-gray-500 font-medium">
                                                    Cantidad: {{ $cproducto->cantidad }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $total += $cproducto->producto->precio_interno * $cproducto->cantidad;
                                    @endphp
                                @endforeach
                            </div>
                            <div class="flex justify-start  mt-2">
                                <div class="text-gray-700 font-medium text-sm">
                                    Total: {{ App\Helpers\Helper::formatearMonto($total) }} Gs.

                                </div>

                            </div>
                        </div>
                    @endif

                    <!-- MUESTRA LOS PRODUCTOS DE LA SESSION('CONSUMO')-->
                    @php
                        $consumo = session('consumo')[$consultaToEdit->id] ?? [];
                    @endphp

                    @if (!empty($consumo))
                        <div class="mt-4">
                            <p class="text-gray-700 font-medium text-sm mb-2">Insumos</p>
                            <div class="grid grid-cols-4 gap-1.5">
                                @foreach ($consumo as $index => $item)
                                    <div
                                        class="relative group bg-gray-100 shadow-lg rounded-md border border-gray-100 hover:border-red-400 transition-all">
                                        <!-- Botón de eliminación -->
                                        <button
                                            wire:click='quitarProducto({{ $index }}, {{ $consultaToEdit->id }})'
                                            class="absolute hidden group-hover:flex items-center justify-center inset-0 bg-white/50 cursor-pointer">
                                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>

                                        <!-- Contenido del producto -->
                                        <div class="flex flex-col items-center p-2">
                                            <img class="w-12 h-12 object-cover rounded-md mb-1 text-xs"
                                                src="{{ asset('uploads/productos/' . $item['foto']) }}"
                                                alt="{{ $item['nombre'] }}">
                                            <div class="text-center">
                                                <p
                                                    class="text-xs font-medium text-gray-700 leading-tight mb-0.5 line-clamp-2">
                                                    {{ $item['nombre'] }}
                                                </p>
                                                <p class="text-[10px] text-gray-500 font-medium">
                                                    {{ number_format($item['precio'], 0, ',', '.') }} Gs
                                                </p>
                                                <p class="text-[10px] text-gray-500 font-medium">
                                                    Cantidad: {{ $item['cantidad'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="items-center justify-center mt-6">
                            <button type="button" wire:click='updateConsulta'
                                class="cursor-pointer items-center px-4 py-2 bg-gray-800 text-white font-medium rounded-md hover:bg-gray-700 transition duration-300">
                                Guardar
                            </button>
                        </div>
                    @endif

                    <!-- FORMULARIO DE BUSCA DE PRODUCTOS -->
                    <form wire:click.prevent='filtrarProductos'>
                        <div class="mb-5 mt-5">
                            <label class="block text-gray-800 font-medium mb-2">Agregar consumo de insumos</label>
                            <div class="relative w-4/7 max-w-md">
                                <input type="text" wire:model='q'
                                    class="h-8 w-full pl-4 pr-10 py-2 border border-gray-600 rounded-lg shadow-lg bg-gray-100 focus:ring focus:gray-600 focus:gray-600 outline-none"
                                    placeholder="Buscar insumos...">

                                @if ($q)
                                    <button type="button" wire:click="flag"
                                        class="absolute inset-y-0 right-10 flex items-center px-1 py-0.5 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition">
                                        ✕
                                    </button>
                                @endif

                                <button type="submit"
                                    class="border-l border-gray-400 pl-2 cursor-pointer absolute inset-y-0 right-3 flex items-center">
                                    <svg class="w-5 h-5 text-gray-700 " aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                            d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                    </svg>
                                </button>
                            </div>

                            <!-- TABLA DE RESULTADOS DE LA BUSQUEDA -->
                            @if ($q)
                                <div>

                                    <p class="text-gray-700 font-medium text-md mt-3">Resultados de la búsqueda</p>
                                    <p class="text-gray-700 font-medium text-sm mb-2">Productos de uso interno, se
                                        venden
                                        por: c/u, ml, mg, g.</p>
                                    <table class="min-w-full bg-white rounded-lg shadow-md text-xs mt-2">
                                        <thead class="bg-gray-200 text-gray-800 rounded-lg">
                                            <tr>
                                                <th class="py-1 px-2 text-left text-semibold sr-only">foto</th>
                                                <th class="py-1 px-2 text-left text-semibold">Producto</th>
                                                <th class="py-1 px-2 text-left text-semibold">Precio Gs.</th>
                                                <th class="py-1 px-2 text-left text-semibold sr-only">acction</th>
                                            </tr>
                                        </thead>

                                        <tbody class="text-gray-800 z-50">
                                            @foreach ($productos as $producto)
                                                <tr wire:key='{{ $producto->id }}'
                                                    class="hover:bg-gray-100 transition duration-300">
                                                    <td class="py-1 px-2 overflow-visible"><img
                                                            class="w-12 h-12  rounded-md"
                                                            src="{{ asset("uploads/productos/$producto->foto") }}"
                                                            alt="" srcset=""></td>
                                                    <td class="py-1 px-2">{{ $producto->nombre }}</td>
                                                    <td class="py-1 px-2">
                                                        {{ App\Helpers\Helper::formatearMonto($producto->precio_interno) }}
                                                    </td>
                                                    <td class="py-1 px-2">
                                                        <span
                                                            wire:click='addProducto({{ $producto->id }}, {{ $consultaToEdit->id }})'
                                                            class="bg-gray-800 p-0.5 text-white cursor-pointer hover:bg-gray-500">
                                                            ADD
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            @endif

            <!-- FORMULARIO PARA ACTUALIZAR LA CONSULTA COMPLETA-->
            <form wire:submit.prevent='updateConsulta'>
                @php
                    $estados = [
                        'Agendado' => 'bg-[#007bff]',
                        'Reprogramado' => 'bg-[#6f42c1]',
                        'Pendiente' => 'bg-[#fd7e14]',
                        'En Espera' => 'bg-[#ffc107]',
                        'En consultorio' => 'bg-[#28a745]',
                        'Finalizado' => 'bg-[#155724]',
                        'No asistió' => 'bg-[#6c757d]',
                        'Cancelado' => 'bg-[#dc3545]',
                    ];
                @endphp

                <!-- CAMBIAR ESTADO -->
                <div class="mb-5 mt-5">
                    <label class="block text-gray-800 font-medium mb-2">Cambiar Estado</label>
                    <select wire:model='estado' name="" id=""
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                        <option value="">-Seleccionar-</option>
                        @foreach ($estados as $estado => $color)
                            <option value="{{ $estado }}"
                                {{ $estado == $consultaToEdit->estado ? 'selected' : '' }}>{{ $estado }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- AGREGAR MAS VETERINARIOS A LA CONSULTA -->
                <div class="mb-4 mt-5">
                    <label class="block text-gray-800 font-medium mb-2">Agregar Veterinario</label>
                    @php
                        $veterinariosEnConsulta = App\Models\ConsultaVeterinario::where(
                            'consulta_id',
                            $consultaToEdit->id,
                        )
                            ->pluck('veterinario_id')
                            ->toArray();
                        $veterinariosEnConsulta[] = $consultaToEdit->veterinario_id;
                    @endphp
                    @foreach ($veterinarios as $veterinario)
                        @if (!in_array($veterinario->id, $veterinariosEnConsulta))
                            <input wire:model='veterinariosAgg' type="checkbox" name="vet_id" id=""
                                value="{{ $veterinario->id }}">
                            {{ $veterinario->name }} <br>
                        @endif
                    @endforeach
                    @error('veterinario_id')
                        <span class="text-red-700 underline">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Fecha -->
                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Fecha De consulta:</label>
                    <input type="date" wire:model="fechaN"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                    @error('fecha')
                        <span class="text-red-700 underline">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Hora de la consulta</label>
                    <input type="time" wire:model="horaN"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                    @error('fecha')
                        <span class="text-red-700 underline">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tipo -->
                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Tipo de Consulta</label>
                    <select wire:model="tipo"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                        @foreach ($tipoConsultas as $tipoC)
                            <option value="{{ $tipoC->id }}"
                                {{ $tipoC->id == $consultaToEdit->tipo_id ? 'selected' : '' }}>{{ $tipoC->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo')
                        <span class="text-red-700 underline">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Síntomas -->
                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Síntomas</label>
                    @if (!empty($sintomas))
                        <input type="checkbox" wire:model='flagSintomas'> <span class="text-xs">Eliminar?</span>
                    @endif
                    <textarea wire:model="sintomas"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100"
                        rows="3" placeholder="Describe los síntomas">{{ $consultaToEdit->sintomas }}</textarea>
                    @error('sintomas')
                        <span class="text-red-700 underline">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Diagnóstico -->
                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Diagnóstico</label>
                    @if (!empty($diagnostico))
                        <input type="checkbox" wire:model='flagDiagnostico'> <span class="text-xs">Eliminar?</span>
                    @endif
                    <textarea wire:model="diagnostico"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100"
                        rows="3">{{ $consultaToEdit->diagnostico }}</textarea>
                    @error('diagnostico')
                        <span class="text-red-700 underline">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tratamiento -->
                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Tratamiento</label>
                    @if (!empty($tratamiento))
                        <input type="checkbox" wire:model='flagTratamiento'> <span class="text-xs">Eliminar?</span>
                    @endif
                    <textarea wire:model="tratamiento"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100"
                        rows="3" placeholder="Describe el tratamiento">{{ $consultaToEdit->tratamiento }}</textarea>
                    @error('tratamiento')
                        <span class="text-red-700 underline">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Notas -->
                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Notas adicionales</label>
                    @if (!empty($notas))
                        <input type="checkbox" wire:model='flagNotas'> <span class="text-xs">Eliminar?</span>
                    @endif
                    <textarea wire:model="notas"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100"
                        rows="3" placeholder="Añade notas adicionales">{{ $consultaToEdit->notas }}</textarea>
                    @error('notas')
                        <span class="text-red-700 underline">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botón de envío -->
                <div class="flex flex-col gap-2">
                    <button type="submit"
                        class="w-full px-6 py-2 bg-gray-800 text-white font-medium rounded-md hover:bg-gray-700 transition duration-300">
                        Guardar
                    </button>
                    <button type="button" wire:click='eliminarConsultaTrue'
                        class="w-full px-6 py-2 bg-gray-800 text-white font-medium rounded-md hover:bg-gray-700 transition duration-300">
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @if ($modalProductoConsulta)
        @include('includes.consultas.modalEliminar')
    @endif
</div>
