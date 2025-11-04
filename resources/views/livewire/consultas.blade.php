<div class="">
    <!-- Sidebar -->
    <div class="">
        @include('includes.sidebar-add-dueno')
    </div>

    <main class="ml-0 md:ml-64 md:pl-20 md:pt-2 pt-16 pl-2 pr-4">
        <p class="pl-1 py-7 text-4xl font-semibold">Consultas <span class="text-lg"> | Historial clinico</span></p>
        <div class="mb-4 rounded-lg">
            <div class="bg-gray-200 rounded-lg ">
                <div class="p-4">
                    <button wire:click='opneAddConsulta'
                        class="p-2 border border-gray-900 text-white rounded-lg bg-gray-800 cursor-pointer font-semibold hover:bg-gray-700 hover:font-bold">
                        Registrar Consulta <span>+</span>
                    </button>

                    <button wire:click='openTipoConsulta'
                        class="p-2 border border-gray-700 text-gray-900 rounded-lg bg-gray-200 cursor-pointer font-semibold hover:bg-gray-300 hover:font-bold">
                        Agregar Tipo de Consulta <span>+</span>
                    </button>
                </div>
                <!-- alerta de consultas agendadas -->
                <div class="px-4">
                    @livewire('alerta-agendados')
                </div>
                <!-- formulario de busqueda -->
                @include('includes.consultas.busqueda-form')
            </div>
            @php
                $estados = [
                    'Agendado' => 'from-sky-400 to-sky-500 hover:from-sky-500 hover:to-sky-600',
                    'En seguimiento' => 'from-indigo-500 to-indigo-700 hover:from-indigo-600 hover:to-indigo-800',
                    'Internado' => 'from-red-700 to-red-800 hover:from-red-800 hover:to-red-900',
                    'Pendiente' => 'from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600',
                    'En recepción' => 'from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700',
                    'En consultorio' => 'from-green-500 to-green-600 hover:from-green-600 hover:to-green-700',
                    'Finalizado' => 'from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500',
                    'No asistió' => 'from-gray-400 to-gray-500 hover:from-gray-500 hover:to-gray-600',
                    'Cancelado' => 'from-rose-500 to-rose-700 hover:from-rose-600 hover:to-rose-800',
                ];

                $estadosf = [
                    'Agendado' => 'bg-sky-200 border-sky-300',
                    'En seguimiento' => 'bg-indigo-200 border-indigo-300',
                    'Internado' => 'bg-red-200 border-red-300',
                    'Pendiente' => 'bg-amber-100 border-amber-300',
                    'En recepción' => 'bg-yellow-100 border-yellow-300',
                    'En consultorio' => 'bg-green-200 border-green-300',
                    'Finalizado' => 'bg-gray-200 border-gray-300',
                    'No asistió' => 'bg-gray-200 border-gray-400',
                    'Cancelado' => 'bg-rose-200 border-rose-300',
                ];
            @endphp
            <!-- cards de las consultas -->
            @include('includes.consultas.card')
    </main>
    @if ($addConsulta)
        @include('includes.consultas.modalAdd')
    @endif

    @if ($modalConfig)
        @include('includes.consultas.modalConfig')
    @endif
    @if ($tipoConsulta)
        @include('includes.consultas.modalTipoConsulta')
    @endif
    @if ($mascotasBusqueda)
        @include('includes.consultas.mascotas')
    @endif
    <script>
        function cambiarColor(select) {
            let colores = @json($estados);
            select.className =
                "estado-select absolute top-2 left-2 z-10 px-4 py-2 text-xs font-semibold text-white rounded-lg bg-gradient-to-r " +
                (colores[select.value] || "from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500");
        }

        function cambiarColorCard(id, estado) {
            let coloresf = @json($estadosf);
            let card = document.getElementById(`consulta-${id}`);

            if (card) {
                card.className =
                    `max-w-[270px] shadow-md rounded-lg overflow-hidden transition-all duration-200 
                              hover:scale-101 hover:shadow-lg relative ${coloresf[estado] || 'bg-gray-300 border-gray-400'}`;
            }
        }

        function mostarMensaje() {
            let funcion = document.getElementById('funcion');
            let mensaje = document.getElementById('mensaje');
            if (funcion && mensaje) {
                funcion.addEventListener('click', function() {
                    mensaje.classList.toggle('hidden');
                });
            }
        }

        // Ejecutar al cargar la página
        document.addEventListener('DOMContentLoaded', mostarMensaje);
    </script>
</div>
