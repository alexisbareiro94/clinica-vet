<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\TipoConsulta;
use App\Models\MovimientoProduct;
use App\Models\Producto;
use App\Models\Especie;
use Livewire\Component;
use Carbon\Carbon;

class Reportes extends Component {
    public string $search = '';
    public string $desde = '';
    public string $hasta = '';       
    public string $filtroTag = '';
    public $filtroPor = 1;
    public object $especies;
    public object $productos;    
    public object $ventas;    
    public bool $fechas = false;
    public bool $filtro = false;

    public function ownerId(): mixed{
        $requestUserId = Auth::user()->id;
        $user = User::find($requestUserId);
        if($user->admin){
            $admin_id = $user->id;
        }else{
            $admin_id = $user->admin_id;
        }
        if($admin_id == null){
            return back()->with('error', 'No tienes permisos para agregar una mascota');
        } 
        return $admin_id;
    }
    public function setFiltroPor($filtroPor) : void {  
        if (Auth::user()->plan_id == 1 || Auth::user()->plan_id == 2){
            return;
        }      
        if($filtroPor == 1){
            $this->filtroPor = 1;
            $this->ventas = Producto::where('ventas', '!=', 0)
                                ->where('owner_id', $this->ownerId())
                                ->orderBy('ventas', 'desc')
                                ->get();
            $this->filtro = false;
        }else if($filtroPor == 2){
            $this->filtroPor = 2;
            $this->ventas = TipoConsulta::where('veces_realizadas' , '!=', 0)
                                ->where('owner_id', $this->ownerId())
                                ->orderBy('veces_realizadas', 'desc')
                                ->get();    
            $this->filtro = false;  
        }
    }

    /**
     * 
     */
    public function filtroTrue(){    
        if (Auth::user()->plan_id == 1 || Auth::user()->plan_id == 2){
            return;
        }              
        $this->filtro = true;
        
    }
    public function filtroFalse(){
        $this->filtro = false;        
    }
    public function refresh(){     
        $this->search = '';
        $this->desde = '';
        $this->hasta = '';
        $this->fechas = false;
        $this->filtroTag = '';
        $this->filtroFalse();
        $this->mount();   
    }

    public function mount() : void {
        $this->especies = Especie::where('owner_id', $this->ownerId())->get();
        $this->productos = Producto::where('owner_id', $this->ownerId())->get();
        $this->ventas = Producto::where('ventas', '!=', 0)
                                ->where('owner_id', $this->ownerId())
                                ->orderBy('ventas', 'desc')
                                ->get();                                                                                                           
    }

    public function fechasTrue() : void { 
        if (Auth::user()->plan_id == 1 || Auth::user()->plan_id == 2){
            return;
        }                  
        $this->fechas = true;                     
    }
    public function fechasFalse() : void {
        if (Auth::user()->plan_id == 1 || Auth::user()->plan_id == 2){
            return;
        }          
        $this->fechas = false;        
    }    
    public function filtrar(){   
        if (Auth::user()->plan_id == 1 || Auth::user()->plan_id == 2){
            return;
        }                     
        if($this->search == ''){      
            $this->desde = empty($this->desde) ? now()->startOfDay()->format('Y-m-d H:s:i') : Carbon::parse($this->desde)->format('Y-m-d H:s:i'); 
            $this->hasta = empty($this->hasta) ? now()->endOfDay()->format('Y-m-d H:s:i') :  Carbon::parse($this->hasta)->format('Y-m-d H:s:i');
            
            $this->ventas = MovimientoProduct::join('productos', 'mivimiento_products.producto_id', '=', 'productos.id')
                                            ->where('productos.ventas', '!=', 0)
                                            ->where('productos.owner_id', $this->ownerId())
                                            ->whereNotNull('mivimiento_products.producto_id')
                                            ->whereBetween('mivimiento_products.created_at', [$this->desde, $this->hasta])    
                                            ->orderBy('productos.ventas', 'desc')
                                            ->get();
            $this->filtroTag = 'Fecha';
        }else{
            if($this->desde == '' and $this->hasta == ''){
                $this->ventas = MovimientoProduct::join('productos', 'mivimiento_products.producto_id', '=', 'productos.id')
                                                ->whereNotNull('mivimiento_products.producto_id')        
                                                ->where('productos.owner_id', $this->ownerId())                                    
                                                ->where('productos.nombre', 'like', '%'.$this->search.'%')                                           
                                                ->orderBy('productos.ventas', 'desc')
                                                ->get();
                $this->filtroTag = $this->search;
            }else{
                $this->desde = empty($this->desde) ? now()->startOfDay()->format('Y-m-d H:s:i') : Carbon::parse($this->desde)->format('Y-m-d H:s:i'); 
                $this->hasta = empty($this->hasta) ? now()->endOfDay()->format('Y-m-d H:s:i') :  Carbon::parse($this->hasta)->format('Y-m-d H:s:i');
                
                $this->ventas = MovimientoProduct::join('productos', 'mivimiento_products.producto_id', '=', 'productos.id')
                                                ->where('productos.owner_id', $this->ownerId())
                                                ->whereNotNull('mivimiento_products.producto_id')
                                                ->whereBetween('mivimiento_products.created_at', [$this->desde, $this->hasta])    
                                                ->where('productos.nombre', 'like', '%'.$this->search.'%')                                           
                                                ->orderBy('productos.ventas', 'desc')
                                                ->get();                
                $this->filtroTag = 'Búsqueda y Fecha';
            }                           
        }                
        $this->filtroFalse();        
    }  

    public function render() {
        return view('livewire.reportes');
    }
}
