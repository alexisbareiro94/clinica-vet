<div id="confirmarModal" tabindex="-1"
    class=" fixed top-0 right-0 left-0 z-40 flex justify-center items-center w-full h-full bg-black/50">
    <div class="relative p-4 w-full max-w-md">

        <button type="button"
            class="m-2 absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
            wire:click="closeModalAgregar">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Cerrar</span>
        </button>

        <form wire:submit='store' enctype="multipart/form-data"
            class="bg-white border border-gray-100 p-8 max-w-md mx-auto shadow-lg rounded-lg max-h-[620px] outline-none overflow-x-hidden overflow-y-auto">
            <p class="text-2xl font-semibold text-center text-gray-800 mb-6">Agregar Producto</p>

            <!-- Nombre -->
            <div class="mb-4">
                <label class="block text-gray-800 font-medium mb-2">Nombre</label>
                <input wire:model="nombre" type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                @error('nombre')
                    <div class="text-sm text-red-600 bg-red-100 border border-red-300 rounded-md px-3 py-2 mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Codigo -->
            <div class="mb-4">
                <div class="flex">
                    <label class="mr-2 block text-gray-800 font-medium mb-2">Código</label>
                    <input class="mb-2 mr-1 cursor-pointer" type="checkbox" wire:model="flagCodigo" id="">
                    <p class="text-xs mt-1">Código Automático</p>
                </div>
                <input wire:model="codigo" type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                @error('nombre')
                    <div class="text-sm text-red-600 bg-red-100 border border-red-300 rounded-md px-3 py-2 mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="mb-4">
                <label class="block text-gray-800 font-medium mb-2">Descripción</label>
                <textarea wire:model="descripcion"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100"></textarea>
                @error('descripcion')
                    <div class="text-sm text-red-600 bg-red-100 border border-red-300 rounded-md px-3 py-2 mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Categoría -->
            <div class="mb-4">
                <label class="block text-gray-800 font-medium mb-2">Categoría</label>
                <select wire:model="categoria_id" id=""
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
                @error('categoria')
                    <div class="text-sm text-red-600 bg-red-100 border border-red-300 rounded-md px-3 py-2 mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Proveedor -->
            <div class="mb-4">
                <label class="block text-gray-800 font-medium mb-2">Proveedor</label>
                <select wire:model="proveedor_id" id=""
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                    <option value="">Seleccione un proveedor</option>
                    @foreach ($proveedores as $proveedor)
                        <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                    @endforeach
                </select>
                @error('categoria')
                    <div class="text-sm text-red-600 bg-red-100 border border-red-300 rounded-md px-3 py-2 mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Precio -->
            <div class="mb-4">
                <label class="block text-gray-800 font-medium mb-2">Precio</label>
                <input wire:model="precio" name="precio" type="number" step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                @error('precio')
                    <div class="text-sm text-red-600 bg-red-100 border border-red-300 rounded-md px-3 py-2 mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Precio de Compra -->
            <div class="mb-4">
                <label class="block text-gray-800 font-medium mb-2">Precio de Compra</label>
                <input wire:model="precio_compra" name="precio_compra" type="number" step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                @error('precio_compra')
                    <div class="text-sm text-red-600 bg-red-100 border border-red-300 rounded-md px-3 py-2 mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Stock Actual -->
            <div class="mb-4">
                <label class="block text-gray-800 font-medium mb-2">Stock Actual</label>
                <input wire:model="stock_actual" name="stock_actual" type="number"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                @error('stock_actual')
                    <div class="text-sm text-red-600 bg-red-100 border border-red-300 rounded-md px-3 py-2 mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Foto -->
            <div class="mb-4">
                <label class="block text-gray-800 font-medium mb-2">Foto</label>
                <input wire:model="foto" name="foto" type="file"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                @error('foto')
                    <div class="text-sm text-red-600 bg-red-100 border border-red-300 rounded-md px-3 py-2 mt-1">
                        {{ $message }}
                    </div>
                @enderror
                <!-- preview image -->
                @if ($imagePreview)
                    <div class="mt-2 relative w-36 h-36">
                        <button wire:click="removeImage" type="button"
                            class="absolute top-0 right-0 mt-2 mr-2 px-1 py-0.5 backdrop-blur-md rounded-full text-gray-400 text-sm cursor-pointer hover:text-gray-900 hover:bg-white/50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <img class="w-36 h-36 rounded-md object-cover" src="{{ $imagePreview }}" alt="Vista previa">
                    </div>
                @endif
            </div>

            @if (Auth::user()->plan_id == 1)
                <div class="mt-4 mb-4 p-4 bg-gray-100 rounded border border-dashed border-gray-400 text-gray-500">
                    <h3 class="font-bold">Uso interno</h3>
                    <p>No disponible para este plan.</p>
                    <button class="cursor-pointer font-semibold mt-2 px-3 py-1 bg-gray-300 rounded text-sm transition-all duration-500 hover:bg-gradient-to-r hover:from-green-200 hover:to-green-300 hover:text-green-600 hover:font-semibold">
                        Actualizar a plan avanzado
                    </button>
                </div>
            @else
                <!-- opciones de uso interno -->
                <div class=" my-4 pt-5">
                    <p class="text-2xl font-semibold text-center text-gray-800 mb-2 block">Opciones para uso interno
                    </p>
                    <div class="py-6 flex gap-1">
                        <p>Marcar solo para uso interno:</p>
                        <input wire:model='usoInterno' class="cursor-pointer p-3" type="checkbox"
                            id="solo-uso-interno">
                    </div>
                    <p class="block text-gray-800 font-medium mb-2">Precios de uso interno:</p>
                    <div class="flex items-center mb-4 gap-2">
                        <select wire:model="unidades" id="unidades"
                            class="w-24 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                            <option value="" selected>-- Elegir unidad --</option>
                            <option value="cu">Unidad</option>
                            <option value="ml">Mililitro (ml)</option>
                            <option value="mg">Miligramo (mg)</option>
                            <option value="gr">Gramo (gr)</option>
                        </select>

                        <input value="1" type="number" wire:model="cantidad"
                            class="py-2 px-2 w-16 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">

                        <div class="relative">
                            <input
                                class="z-30 py-2 px-3 w-full border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100"
                                type="number" wire:model="precio_interno">
                            <i class="absolute top-2.5 right-4 z-40 border-l pl-2 bg-white">
                                Gs.
                            </i>
                        </div>
                    </div>
                    @error('unidades')
                        <div class="text-sm text-red-600 bg-red-100 border border-red-300 rounded-md px-3 py-2 mt-1">
                            Campos obligatorios
                        </div>
                    @enderror
                    <!-- opciones de cantidad -->
                    <p class="block text-gray-800 font-medium mb-2">Opciones de cantidad:</p>
                    <div class="flex items-center mb-4 gap-2">
                        <select wire:model="capacidad" id="capacidad"
                            class="w-1/4 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                            <option value="" selected>-- Elegir unidad --</option>
                            <option value="u">Unidad</option>
                            <option value="ml">Mililitro (ml)</option>
                            <option value="mg">Miligramo (mg)</option>
                            <option value="gr">Gramo (gr)</option>
                        </select>

                        <input value="1" type="number" wire:model="cantidadCapacidad"
                            class="py-2 px-2 w-1/4 border border-gray-300 rounded-md focus:outline-none focus:border-gray-800 focus:bg-gray-100">
                    </div>
                    @error('capacidad')
                        <div class="text-sm text-red-600 bg-red-100 border border-red-300 rounded-md px-3 py-2 mt-1">
                            Campos obligatorios
                        </div>
                    @enderror
                    @error('cantidadCapacidad')
                        <div class="text-sm text-red-600 bg-red-100 border border-red-300 rounded-md px-3 py-2 mt-1">
                            Campos obligatorios
                        </div>
                    @enderror
                </div>
            @endif
            <!-- Botón de enviar -->
            <button type="submit"
                class="w-full bg-gray-800 text-white font-medium py-2 rounded-md hover:bg-black transition duration-300">
                Guardar Producto
            </button>
        </form>
    </div>
</div
