<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\UsoInterno;
use App\Helpers\Helper;
use App\Models\Caja;
use App\Models\Vacunacion;
use App\Models\ConsultaProducto;
use App\Models\TipoConsulta;
use App\Models\Producto;
use App\Models\ConsultaVeterinario;
use App\Models\Consulta;
use App\Models\Rol;
use App\Models\User;
use App\Models\Mascota;
use App\Mail\RecordatorioConsulta;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

#[Title('Consultas')]
class Consultas extends Component
{
    public $search = '';
    public $mascotas;
    public $veterinarios;
    public $users;
    public $mascota_id, $veterinario_id, $fecha, $tipo, $sintomas, $diagnostico, $tratamiento, $notas, $hora, $estado;
    public $fechaN, $horaN;
    public $consultas;
    public $cambiarVet = false;
    public $vetChanged = '';
    public $addConsulta = false;
    public $modalConfig = false;
    public $consultaToEdit;
    public $message = false;
    public $cambiarVetId = '';
    public $productos;
    public $tipoConsulta = false;
    public $nombre, $descripcion, $precio;
    public $tipoConsultas;
    public $tablaTipoConsulta = false;
    public $tablaDeProductos = false;
    public $productoConsumido;
    public $q;
    public $consultasProductos;
    public $veterinariosAgg = [];
    public $modalProductoConsulta = false;
    public $cpId;
    public $flagDiagnostico, $flagSintomas, $flagTratamiento, $flagNotas;
    public $grupoVet;
    public $pagos;
    public $estadosf = [
        'Agendado',
        'En seguimiento',
        'Internado',
        'Pendiente',
        'En recepción',
        'En consultorio',
        'Finalizado',
        'No asistió',
        'Cancelado',
    ];
    public $estadofiltrado = '';
    public bool $mascotasBusqueda = false;
    public string $mascotaSearch = '';
    public ?object $mascotaResultado;
    public ?object $mascotaSelect;
    public ?object $cajas = null;

    /**
     * 
     */
    public function ownerId()
    {
        if (Auth::user()) {
            $requestUserId = Auth::user()->id;
            $user = User::find($requestUserId);
            if ($user->admin) {
                $admin_id = $user->id;
            } else {
                $admin_id = $user->admin_id;
            }
            if ($admin_id == null) {
                return back()->with('error', 'No tienes permisos para agregar una mascota');
            }
            return $admin_id;
        }
    }
    /** 
     * 
     */
    public function mascotasBusquedaTrue(): void
    {
        $this->mascotaResultado = Mascota::where('nombre', 'like', "%$this->mascotaSearch%")
            ->where('owner_id', $this->ownerId())
            ->get();
        $this->mascotasBusqueda = true;
    }
    public function mascotasBusquedaFalse(): void
    {
        $this->mascotasBusqueda = false;
    }

    /**
     * 
     */
    public function selectMascota($mascotaId): void
    {
        $this->mascota_id = $mascotaId;
        $this->mascotasBusqueda = false;
        $this->mascotaSearch = '';
        $this->mascotaSearch = Mascota::find($mascotaId)->nombre;
    }

    /**
     * function para la busqueda
     */
    public function busqueda(): void
    {
        if (empty($this->search)) {
            $this->consultas = Consulta::orderBy('id', 'desc')
                ->where('owner_id', $this->ownerId())
                ->take(12)
                ->get();
        } else {
            $mascotas = Mascota::whereLike('nombre', "%$this->search%")
                ->where('owner_id', $this->ownerId())
                ->pluck('id');
            $this->consultas = Consulta::whereIn('mascota_id', $mascotas)
                ->orWhereLike('codigo', "%$this->search%")
                ->where('owner_id', $this->ownerId())
                ->get();
        }
    }
    public function flagC(): void
    {
        $this->search = '';
        $this->consultas = Consulta::orderBy('id', 'desc')
            ->where('owner_id', $this->ownerId())
            ->take(12)->get();
    }

    /**
     * function para elimiar un veterinario del grupo
     */
    public function eliminarVetGrupo($vetId)
    {
        try {
            ConsultaVeterinario::find($vetId)?->delete();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        $this->dispatch('success', 'Veterinario eliminado del grupo');
    }

    /**
     * 
     */
    public function vaciarVariables(): void
    {
        $this->mascota_id = null;
        $this->veterinario_id = null;
        $this->fecha = null;
        $this->tipo = '';
        $this->sintomas = '';
        $this->diagnostico = '';
        $this->tratamiento = '';
        $this->notas = '';
        $this->hora = null;
        $this->estado = null;
    }

    /**
     * 
     */
    public function openProductoConsulta($cpId): void
    {
        $this->cpId = $cpId;
        $this->modalProductoConsulta = true;
    }
    public function closeProductoConsulta(): void
    {
        $this->modalProductoConsulta = false;
    }

    /**
     * comprueba que hay ningun array vacio 
     */
    public function comprobarSession(): void
    {
        $sessionConsumo = session('consumo', []);

        // Filtrar los valores vacíos
        $sessionConsumo = array_filter($sessionConsumo, function ($productos) {
            return !empty($productos);
        });

        if (empty($sessionConsumo)) {
            Session::forget('consumo');
        } else {
            session(['consumo' => $sessionConsumo]);
        }
    }
    /**
     * 
     */
    public function openProductoConsumido(): void
    {
        $this->productoConsumido = true;
    }
    public function closeProductoConsumido(): void
    {
        $this->productoConsumido = false;
    }

    /**
     * 
     */
    public function filtrarProductos(): void
    {
        if (Auth::user()->plan_id == 1) {
            return;
        }
        $this->openProductoConsumido();
        if (empty($this->q)) {
            $this->productos = Producto::take(0)->get();
        } else {
            $this->openProductoConsumido();
            $this->productos = Producto::whereLike('nombre', "%$this->q%")
                ->where('owner_id', $this->ownerId())
                ->where('stock_actual', '>', 1)
                ->where('precio_interno', '!=', null)
                ->get();
        }
    }

    public function flag(): void
    {
        $this->q = '';
    }

    /**
     * creacion de la sesion de productos
     */
    public function addProducto($productoId, $consultaId)
    {
        $producto = Producto::where('id', $productoId)
            ->where('owner_id', $this->ownerId())
            ->first();
        if (!$producto) {
            return redirect()->route('consultas')->with('error', 'Hubo un error al procesar el producto');
        }
        $consumo = session('consumo', []);
        if (!isset($consumo[$consultaId])) {
            $consumo[$consultaId] = [];
        }
        $contador = 0;
        foreach ($consumo[$consultaId] as &$item) {
            if ($item['productoId'] == $producto->id) {
                if ($item['productoCompleto']['precio_interno'] == $item['precio']) {
                    $item['cantidad']++;
                    $contador++;
                    break;
                } else {
                    return back();
                }
            }
        }
        if ($contador == 0) {
            $consumo[$consultaId][] = [
                'consultaId' => $consultaId,
                'productoId' => $producto->id,
                'precio' => $producto->precio_interno,
                'nombre' => $producto->nombre,
                'foto' => $producto->foto,
                'productoCompleto' => $producto,
                'cantidad' => 1
            ];
        }

        // Caja::update([
        //     'producto_consulta_id' 
        // ]);
        session(['consumo' => $consumo]);
        if (count(session('consumo')) == 0) {
            $this->dispatch('success', 'Producto agregado a la consulta');
        }
    }

    /**
     * function que quita una unidad de la session consumos
     */
    public function quitarProducto($index, $consultaId)
    {
        if (Auth::user()->plan_id == 1) {
            return;
        }
        $consumo = session('consumo', []);

        if (!isset($consumo[$consultaId][$index])) {
            return redirect()->route('consultas')->with('error', 'El producto no existe en la sesión');
        }

        if ($consumo[$consultaId][$index]['cantidad'] > 1) {
            $consumo[$consultaId][$index]['cantidad']--;
        } else {
            unset($consumo[$consultaId][$index]);
            session(['consumo' => $consumo]);
            //   return redirect()->route('consultas');
        }
        session(['consumo' => $consumo]); // Guardar la sesión después de modificar        
    }


    /**
     * 
     */
    public function openTablaDeProducto(): void
    {
        $this->tablaDeProductos = true;
    }
    public function closeTablaDeProductos(): void
    {
        $this->tablaDeProductos = false;
    }

    /**
     * 
     */
    public function openTablaTipoConsulta(): void
    {
        $this->tablaTipoConsulta = true;
    }
    public function closeTablaTipoConsulta(): void
    {
        $this->tablaTipoConsulta = false;
    }

    /**
     * 
     */
    public function openTipoConsulta()
    {
        $this->tipoConsulta = true;
    }
    public function closeTipoConsulta()
    {
        $this->tipoConsulta = false;
    }
    public function crearTipoConsulta()
    {
        $this->validate([
            'nombre' => 'required',
            'descripcion' => 'nullable',
            'precio' => 'required',
        ]);
        $requestUserId = Auth::user()->id;
        $user = User::find($requestUserId);
        if ($user->admin) {
            $admin_id = $user->id;
        } else {
            $admin_id = $user->admin_id;
        }
        if ($admin_id == null) {
            return back()->with('error', 'No tienes permisos para agregar una mascota');
        }
        try {
            TipoConsulta::create([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'precio' => $this->precio,
                'owner_id' => $admin_id,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('tipoconsulta-notadd');
        }

        $this->dispatch('tipoconsulta-add');
    }

    /**
     * 
     */
    public function openCambiarVet(): void
    {
        $this->cambiarVet = true;
    }
    public function closeCambiarVet(): void
    {
        $this->vetChanged = '';
        $this->cambiarVet = false;
    }
    public function setVetChanged($vetId): void
    {
        $this->vetChanged = $vetId;
    }
    public function showMessage(): void
    {
        $this->message = true;
    }
    public function closeMessage(): void
    {
        $this->message = false;
    }


    /**
     * 
     */
    public function openModalConfig($consultaId)
    {
        $this->consultaToEdit = Consulta::where('id', $consultaId)->where('owner_id', $this->ownerId())->first();
        $this->horaN = $this->consultaToEdit->hora;
        $this->fechaN = $this->consultaToEdit->fecha;
        $this->tipo = $this->consultaToEdit->tipo_id;
        $this->notas = $this->consultaToEdit->notas;
        $this->sintomas = $this->consultaToEdit->sintomas;
        $this->diagnostico = $this->consultaToEdit->diagnostico;
        $this->tratamiento = $this->consultaToEdit->tratamiento;
        $this->consultasProductos = ConsultaProducto::where('consulta_id', $consultaId)
            ->where('owner_id', $this->ownerId())
            ->get();
        $this->modalConfig = true;
    }
    public function closeModalConfig()
    {
        Session::forget('consumo');
        $this->flag();
        $this->vetChanged = '';
        $this->consultaToEdit = null;
        $this->modalConfig = false;
    }
    #[On('success')]
    public function success(): void
    {
        if ($this->consultaToEdit != null) {
            $this->openModalConfig($this->consultaToEdit->id);
        }
    }
    private function codigo($length): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * funcion que crea las consultas
     */
    public function crearConsulta() {
        $r = '';
        if(Auth::user()->plan_id == 1 || Auth::user()->plan_id == 2){
            $r = Consulta::where('owner_id', $this->ownerId())->get();
            if($r->count() >= 50){
                return redirect()->route('consultas')->with('error', 'No puedes crear más de 50 consultas en el plan gratuito/Básico');
            }            
        }

        if(Auth::user()->plan_id == 3){
            if($r->count() >= 100){
                return redirect()->route('consultas')->with('error', 'No puedes crear más de 100 consultas en el plan Estándar');
            }
        }

        if(Auth::user()->plan_id == 4){
            if($r->count() >= 200){
                return redirect()->route('consultas')->with('error', 'No puedes crear más de 200 consultas en el plan Profesional');
            }
        }

        if(Auth::user()->plan_id == 5){
            if($r->count() >= 350){
                return redirect()->route('consultas')->with('error', 'No puedes crear más de 350 consultas en el plan Avanzado');

            }
        }
        $this->validate([
            'mascota_id' => 'required',
            'veterinario_id' => 'required',
            'fecha' => 'required',
            'tipo' => 'required',
        ], [
            'mascota_id.required' => 'El campo mascota es obligatorio',
            'veterinario_id.required' => 'El campo veterinario es obligatorio',
            'fecha.required' => 'El campo fecha es obligatorio',
            'tipo.required' => 'El campo tipo de consulta es obligatorio',
        ]);


        try {
            foreach ($this->consultas as $consulta) {
                if ($consulta->mascota_id == $this->mascota_id) {
                    if ($consulta->estado != 'Finalizado' && $consulta->estado != 'Cancelado') {
                        return redirect()->route('consultas')->with('error', "Ya existe una consulta pendiente para esta mascota, Consulta Pendiente: $consulta->tipo" . " - " . "Fecha:  $consulta->fecha" . " - " . "Codigo: $consulta->codigo");
                    }
                }
            }
            if ($this->fecha <= now()->format('Y-m-d')) {
                if ($this->hora < now()->format('H:i')) {
                    return redirect()->route('consultas')->with('error', 'La fecha y hora de la consulta no puede ser menor a la fecha y hora actual');
                }
            }

            if ($this->fecha != now()->format('Y-m-d') or $this->hora != now()->format('H:i')) {
                $this->estado = 'Agendado';
            }

            $consulta = Consulta::create([
                'mascota_id' => $this->mascota_id,
                'veterinario_id' => $this->veterinario_id,
                'fecha' => $this->fecha,
                'tipo_id' => $this->tipo,
                'sintomas' => $this->sintomas,
                'diagnostico' => $this->diagnostico,
                'tratamiento' => $this->tratamiento,
                'notas' => $this->notas,
                'hora' => $this->hora,
                'estado' => $this->estado ?? 'Pendiente',
                'codigo' => $this->codigo(6),
                'owner_id' => $this->ownerId(),
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return redirect()->route('consultas')->with('agregado', 'Consulta creada con éxito');
    }

    /**
     * 
     */
    public function opneAddConsulta(): void
    {
        $this->addConsulta = true;
    }
    public function closeAddConsulta(): void
    {
        $this->vetChanged = '';
        $this->addConsulta = false;
    }

    /**
     * function que actualiza el estado desde la vista principal de las consultas, <select>
     */
    public function updateEstado($consultaID, $estadoNuevo)
    {
        $cambiar = true;
        try {
            $consulta = Consulta::find($consultaID);
            $nombre = $consulta->mascota->nombre;

            foreach ($this->consultas as $consultaC) {
                if ($consultaC->mascota_id == $consulta->mascota_id) {
                    if ($consulta->estado == 'Finalizado' or $consulta->estdo == 'Cancelado' or $consulta->estdo == 'No asistió') {
                        if ($estadoNuevo != 'Finalizado' or $estadoNuevo != 'Cancelado' or $estadoNuevo != 'No asistió') {
                            if ($consulta->estado == 'Finalizado' or $consulta->estdo == 'Cancelado' or $consulta->estdo == 'No asistió') {
                                $this->mount();
                                $this->dispatch('error', 'No se puede cambiar el estado de una consulta finalizada');
                                $cambiar = false;
                                break;
                            }
                            $this->mount();
                            $this->dispatch('error', 'Ya existe una consulta activa para: ' . "$nombre");
                            $cambiar = false;
                            break;
                        }
                    }
                }
            }
            if ($cambiar) {
                $consulta->estado = $estadoNuevo;
                $consulta->save();
            }
        } catch (\Exception $e) {
            return redirect()->route('consultas')->with('error', $e->getMessage());
        }
        if ($cambiar) {
            $this->mount();
            $this->dispatch('success', 'Estado de la consulta actualizado con éxito');
        }
    }


    /**
     * function para cambiar veterinario
     */
    public function updateVet()
    {
        $this->validate([
            'cambiarVetId' => 'sometimes',
        ]);

        try {
            $consulta = Consulta::find($this->consultaToEdit->id);
            $consulta->update([
                'veterinario_id' => $this->cambiarVetId,
            ]);
            $consulta->save();

            ConsultaVeterinario::where('consulta_id', $consulta->id)
                ->where('owner_id', $this->ownerId())
                ->update([
                    'veterinario_id' => $this->cambiarVetId,
                ]);

            $this->openModalConfig($consulta->id);
            //dd($this->consultaToEdit);
            $this->dispatch('success', 'Veterinario actualizado con éxito');
            //$this->consultaToEdit = null;
        } catch (\Exception $e) {
            return redirect()->route('consultas')->with('error', $e->getMessage());
        }
    }
    /**
     * formulario para editar la consulta (productos, tratamiento, síntomas, etc)
     */
    public function updateConsulta(): void
    {
        $consumo = session('consumo', []);
        if (!empty($consumo)) {
            if (Auth::user()->plan_id == 1) {
                return;
            }

            foreach ($consumo as $item) {
                foreach ($item as $value) {
                    $producto = Producto::where('id', $value['productoId'])
                        ->where('owner_id', $this->ownerId())
                        ->first();
                    if (!$producto) {
                        $this->dispatch('error', 'Hubo un error al procesar el producto: ' . $value['nombre']);
                        return;
                    }
                    if ($producto->cantidad_capacidad < $value['cantidad']) {
                        $this->dispatch('error', 'No hay stock suficiente para el producto: ' . $producto->nombre);
                        return;
                    }

                    if ($producto->stock_actual == 0 or $producto->stock_actual < $value['cantidad']) {
                        $this->dispatch('error', 'No hay stock suficiente para el producto: ' . $producto->nombre);
                        return;
                    }

                    $consultaProducto = ConsultaProducto::where('producto_id', $value['productoId'])
                        ->where('consulta_id', $value['consultaId'])
                        ->where('owner_id', $this->ownerId())
                        ->first();

                    if ($consultaProducto) {
                        $consultaProducto->update([
                            'cantidad' => $consultaProducto->cantidad + $value['cantidad'],
                        ]);
                    } else {
                        $consultaProducto = ConsultaProducto::create([
                            'producto_id' => $value['productoId'],
                            'consulta_id' => $this->consultaToEdit->id,
                            'cantidad' => $value['cantidad'],
                            'descripcion' => null,
                            'owner_id' => $this->ownerId(),
                        ]);
                    }
                    $usoInterno = UsoInterno::where('producto_id', $value['productoId'])
                        ->where('consulta_id', $this->consultaToEdit->id)
                        ->where('cantidad', 1)
                        ->where('owner_id', $this->ownerId())
                        ->first();

                    if ($producto->stock_actual < $value['cantidad']) {
                        $this->dispatch('error', 'No hay stock suficiente para el producto: ' . $producto->nombre);
                        return;
                    }

                    if ($this->consultaToEdit->tipoConsulta->nombre == 'Vacunación') {
                        Vacunacion::create([
                            'consulta_id' => $this->consultaToEdit->id,
                            'mascota_id' => $this->consultaToEdit->mascota_id,
                            'producto_id' => $value['productoId'],
                            'aplicada' => true,
                            'proxima_vacunacion' => null,
                            'proxima_vacuna' => null,
                            'fecha_vacunacion' => now()->format('Y-m-d'),
                            'owner_id' => $this->ownerId(),
                            'aplicada' => true,
                        ]);
                    }

                    if ($usoInterno) {
                        if ($producto->sobrante == $value['cantidad']) {
                            $producto->update([
                                'sobrante' => $producto->cantidad_capacidad
                            ]);
                            $usoInterno->update([
                                'cantidad' => 0,
                            ]);
                        } else {
                            $producto->update([
                                'sobrante' => $producto->sobrante - $value['cantidad'],
                            ]);
                        }
                    } else {
                        if ($producto->cantidad_capacidad == $value['cantidad']) {
                            $producto->update([
                                'stock_actual' => $producto->stock_actual - $value['cantidad'],
                                'sobrante' => $producto->sobrante - $value['cantidad'],
                            ]);

                            UsoInterno::create([
                                'producto_id' => $value['productoId'],
                                'consulta_id' => $this->consultaToEdit->id,
                                'owner_id' => $this->ownerId(),
                                'cantidad' => 0,
                            ]);
                        } else {
                            $producto->update([
                                'stock_actual' => $producto->stock_actual - $value['cantidad'],
                                'sobrante' => $producto->sobrante - $value['cantidad'],
                            ]);
                            UsoInterno::create([
                                'producto_id' => $value['productoId'],
                                'consulta_id' => $this->consultaToEdit->id,
                                'owner_id' => $this->ownerId(),
                                'cantidad' => 1,
                            ]);
                        }
                    }
                    if ($producto->sobrante <= 0) {
                        $producto->update([
                            'sobrante' => $producto->cantidad_capacidad,
                        ]);
                    }
                }
            }
        }

        if ($consumo) {
            $productos = [];
            foreach ($consumo[$this->consultaToEdit->id] as $item) { //<- no tocar este foreach
                $productos[] = $item['productoId'];
            }

            if (session('caja')) {
                $consultaProductos = ConsultaProducto::where('consulta_id', $this->consultaToEdit->id)
                    ->where('owner_id', $this->ownerId())
                    ->get();

                $totalProductos = 0;
                foreach ($consultaProductos as $cProducto) {
                    $producto = Producto::where('id', $cProducto->producto_id)
                        ->where('owner_id', $this->ownerId())
                        ->first();
                    $totalProductos += $cProducto->cantidad * (int)$producto->precio_interno;
                }
                $total = $totalProductos + $cProducto->consulta->tipoConsulta->precio;
                $cajadb = Caja::where('consulta_id', $this->consultaToEdit->id)
                    ->where('owner_id', $this->ownerId())
                    ->where('pago_estado', 'Pendiente')
                    ->first();
                $cajadb->update([
                    'monto_total' => 0,
                ]);
                $cajadb->update([
                    'productos' => $productos,
                    'monto_total' => $total,
                ]);
                Session::forget('caja');
                Helper::crearCajas();
            }
        }

        $consulta = Consulta::where('id', $this->consultaToEdit->id)->where('owner_id', $this->ownerId())->first();
        $getEstado = gettype(Helper::updateEstado($this->consultaToEdit->id, $this->estado));

        $estadoNuevo = '';
        if ($getEstado == 'object') {
            $estadoNuevo = $consulta->estado;
        } else {
            $estadoNuevo = Helper::updateEstado($this->consultaToEdit->id, $this->estado);
        }
        $consulta->update([
            'fecha' => $this->fechaN ?? $consulta->fecha,
            'tipo_id' => $this->tipo ?? $consulta->tipo_id,
            'sintomas' => $this->flagSintomas ? null : $this->sintomas,
            'diagnostico' => $this->flagDiagnostico ? null : $this->diagnostico,
            'tratamiento' => $this->flagTratamiento ? null : $this->tratamiento,
            'notas' => $this->flagNotas ? null : $this->notas,
            'hora' => $this->horaN ?? $consulta->hora,
            'estado' => $estadoNuevo ?? $consulta->estado,

        ]);

        if (!empty($this->veterinariosAgg)) {
            foreach ($this->veterinariosAgg as $vetId) {
                ConsultaVeterinario::create([
                    'consulta_id' => $consulta->id,
                    'veterinario_id' => $vetId,
                    'owner_id' => $this->ownerId(),

                ]);
            }
        }

        Session::forget('consumo');
        $this->dispatch('success', 'Consulta Actualizada');
    }

    /**
     * function para eliminar una consultaProdcutos     
     */
    public function EliminarProductoConsulta($cpId)
    {
        try {
            ConsultaProducto::where('id', $cpId)
                ->where('owner_id', $this->ownerId())
                ?->delete();
        } catch (\Exception $e) {
            return redirect()->route('consultas')->with('error', $e->getMessage());
        }
        $this->cpId = '';
        return redirect()->route('consultas')->with('eliminado', 'Producto eliminado de la consulta');
    }

    /**
     * 
     */
    public function filtarPorEstados(): void
    {
        if ($this->estadofiltrado == 1) {
            $this->consultas = Consulta::orderByRaw("
                            CASE 
                                WHEN estado = 'Internado' THEN 1
                                WHEN estado = 'En consultorio' THEN 2
                                WHEN estado = 'En recepción' THEN 3
                                WHEN estado = 'Agendado' THEN 4
                                ELSE 5
                            END")
                ->orderBy('estado', 'desc')
                ->where('owner_id', $this->ownerId())
                ->take(12)
                ->get();
        } else {
            $this->consultas = Consulta::where('estado', $this->estadofiltrado)
                ->where('owner_id', $this->ownerId())
                ->get();
        }
    }

    /**
     * function para disminuir la cantidad de productos en la consulta
     * @param int $consultaId, int $productoId
     * @return void
     */
    public function disminuirCantidad(int $consultaId, int $productoId): void
    {
        if (Auth::user()->plan_id == 1) {
            return;
        }
        $cajadb = Caja::where('consulta_id', $consultaId)
            ->where('owner_id', $this->ownerId())
            ->where('pago_estado', 'Pendiente')
            ->first();

        if ($cajadb) {
            $productos = $cajadb->productos;

            $productoaDisminuir = array_filter($productos, function ($producto) use ($productoId) {
                return $producto == $productoId;
            });
        }

        $consultaProducto = ConsultaProducto::where('consulta_id', $consultaId)
            ->where('producto_id', $productoId)
            ->where('owner_id', $this->ownerId())
            ->first();

        if ($consultaProducto->cantidad > 1) {
            $consultaProducto->update([
                'cantidad' => $consultaProducto->cantidad - 1,
            ]);
            $this->dispatch('success', 'Cantidad disminuida');
        } else if ($consultaProducto->cantidad == 1) {
            $consultaProducto->delete();
            if ($cajadb) {
                $cajadb->update([
                    'productos' => array_diff($productos, $productoaDisminuir),
                ]);
            }
            $this->dispatch('success', 'Cantidad disminuida');
        }

        //para calcular el total de la consulta
        if (session('caja')) {
            $consultaProductos = ConsultaProducto::where('consulta_id', $this->consultaToEdit->id)
                ->where('owner_id', $this->ownerId())
                ->get();
            $total = 0;
            $totalProductos = 0;
            foreach ($consultaProductos as $cProducto) {
                $producto = Producto::where('id', $cProducto->producto_id)
                    ->where('owner_id', $this->ownerId())
                    ->first();
                $totalProductos += $cProducto->cantidad * (int)$producto->precio_interno;
                $total = $totalProductos + $cProducto->consulta->tipoConsulta->precio;
            }
            //dd($total);
            if ($cajadb) {

                $cajadb = Caja::where('consulta_id', $this->consultaToEdit->id)
                    ->where('owner_id', $this->ownerId())
                    ->where('pago_estado', 'Pendiente')
                    ->first();
                $cajadb->update([
                    'monto_total' => 0,
                ]);
                $cajadb->update([
                    'monto_total' => $total,
                ]);
            }
            Session::forget('caja');
            Helper::crearCajas();
        }
    }

    /**
     * Eliminar un tipo de consulta
     *
     * @param int $tipoConsultaId
     * @return RedirectResponse
     */
    public function eliminarTipoConsulta($tipoConsultaId)
    {
        TipoConsulta::where('id', $tipoConsultaId)->where('owner_id', $this->ownerId())->delete();
        return redirect()->route('consultas')->with('eliminado', 'Tipo de consulta eliminado correctamente');
    }

    #[On('disminuirCantidad')]
    public function refresh() {}

    #[On('tipoconsulta-add')]
    public function tipoconsultaAdd()
    {
        $this->tipoConsultas = TipoConsulta::where('owner_id', $this->ownerId())->get();
    }

    public function enviarRecordatorio(int $consultaId) {
        if(Auth::user()->plan_id == 1 || Auth::user()->plan_id == 2 || Auth::user()->plan_id == 3) {
            return;
        }
        $consulta = Consulta::where('id', $consultaId)
            ->where('owner_id', $this->ownerId())
            ->first();

        $consulta->update([
            'recordatorio' => true,
        ]);
        Mail::to($consulta->mascota->dueno->email)->queue(new RecordatorioConsulta($consulta));
        $this->dispatch('success', 'Recordatorio enviado con éxito');
    }

    /**
     * 
     */
    public function eliminarCaja(int $cajaId)
    {
        try {
            Caja::where('id', $cajaId)
                ->where('owner_id', $this->ownerId())
                ->delete();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        $this->dispatch('success', 'Caja eliminada con éxito');
    }


    public function mount()
    {
        Helper::check();
        //devuelve la lista de veterinarios. se muestra en la creacion de la consulta
        $rol = Rol::whereLike('name', "%Vet%")
            ->where('owner_id', $this->ownerId())
            ->first();
        $vetId = $rol->id ?? null;
        $this->veterinarios = User::where('rol_id', $vetId)
            ->where('admin_id', $this->ownerId())
            ->get();
        //devuelve la lista de usuarios que no son veterinarios. se muestra en la creacion de la consulta
        $rol = Rol::whereNotLike('name', "%vet%")
            ->whereNotLike('name', "%user%")
            ->whereNotLike('name', "%admin%")
            ->WhereLike('name', "%pelu%")
            ->orWhereLike('name', "%este%")
            ->orWhereLike('name', "%tica%")
            ->where('owner_id', $this->ownerId())
            ->first();
        $userId = $rol->id ?? null;
        $this->users = User::where('rol_id', $userId)
            ->where('admin_id', $this->ownerId())
            ->get();

        $this->vaciarVariables();

        //inicializa la fecha de la consulta. para que la fecha sea la actual automaticamente
        $this->fecha = now()->format('Y-m-d');
        //inicializa la hora de la consulta. para que la fecha sea la actual automaticamente
        $this->hora = now()->format('H:i');

        $this->mascotas = Mascota::where('owner_id', $this->ownerId())->get();
        $this->consultas = Consulta::orderByRaw("
                            CASE 
                                WHEN estado = 'Internado' THEN 1
                                WHEN estado = 'En consultorio' THEN 2
                                WHEN estado = 'En recepción' THEN 3
                                WHEN estado = 'Agendado' THEN 4
                                ELSE 5
                            END")
            ->orderBy('estado', 'desc') // Si necesitas un segundo ordenamiento por 'estado'
            ->where('owner_id', $this->ownerId())
            ->take(12)
            ->get();

        $this->tipoConsultas = TipoConsulta::where('owner_id', $this->ownerId())->get();
        $this->grupoVet = ConsultaVeterinario::where('owner_id', $this->ownerId())->get();
        $this->hora = now()->addHour()->addMinutes(2)->format('H:i');
        $this->cajas = Caja::where('owner_id', $this->ownerId())
            ->get();
        $this->comprobarSession();
        Session::forget('caja');
        Helper::crearCajas();
        if (empty(session('modulos')['consulta'])) {
            return redirect('/');
        }
    }
    public function render(): View
    {
        return view('livewire.consultas');
    }
}
