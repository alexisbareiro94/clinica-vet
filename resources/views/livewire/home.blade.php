<div class="p-2">
    <nav class="p-4 text-black border border-gray-100 rounded-lg bg-gray-100 shadow-sm relative">
        <div class="container mx-auto flex justify-between items-center relative">
            @if (Auth::user())
                <p class="font-semibold">{{ Str::ucfirst(Auth::user()->name) }}</p>
            @endif
            <h1 class="text-2xl font-bold">
                <a wire:navigate href="{{ route('index') }}">Clínica Veterinaria</a>
                {{-- {{ json_encode($modalLogout) }} --}}
            </h1>
            <button wire:click='logoutModal' class=" cursor-pointer">
                <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2" />
                </svg>
            </button>
        </div>
    </nav>
    
    <!-- Contenedor principal -->
    <div class="container  mx-auto pt-12">        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Gestión de pacientes -->
            @if (session('modulos'))                            
                @if (!empty(session('modulos')['gestionPaciente']))
                    @include('includes.home.gestion-pacientes')
                @endif            

                <!-- consultas -->
                @if (!empty(session('modulos')['consulta']))
                    @include('includes.home.consultas')
                @endif            

                <!-- Caja -->            
                @if (!empty(session('modulos')['caja']))
                    @include('includes.home.caja')                
                @endif            

                <!-- Inventario -->
                @if (!empty(session('modulos')['inventario']))
                    @include('includes.home.inventario')                
                @endif            

                <!-- Gestión de usuarios -->
                @if (!empty(session('modulos')['gestionUsuario']))
                    @include('includes.home.gestion-usuarios')                
                @endif            

                <!-- Reportes -->
                @if (!empty(session('modulos')['reportes']))
                    @include('includes.home.reportes')                
                @endif     
                
                @if (Auth::user()->plan_id < 5)
                    @include('includes.home.plan')
                @endif

                <!-- Alertas y notificaciones -->
                {{-- @if (!empty(session('modulos')['alertas']))
                    @include('includes.home.alertas')                
                @endif     --}}
            @endif        
        </div>
    </div>

    
    @include('includes.home.modal-gestion-pacietes')        
    
    

    @if ($modalLogout)
        @include('includes.home.modal-logout')
    @endif    

    <!-- Agenda de citas -->
    {{-- @include('includes.home.agendas') --}}
    @livewireScripts
</div>
