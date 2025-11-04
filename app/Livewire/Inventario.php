<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use App\Models\Proveedor;
use App\Models\MovimientoProduct;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;


#[Title('Inventario')]
class Inventario extends Component
{
    use WithFileUploads;
    public $productoToEdit = '';
    public string $productoId = '';
    public string $categoria = '';
    public int $verTodo = 9;
    public string $nombreP = '';
    public ?int $telefonoP;
    public ?string $direccionP;
    public ?string $email;
    public ?string $ruc;
    public int $cantidad = 1;
    public string $unidades = '';
    public int $precioInterno = 0;
    public object $categorias;
    public object $productos;
    public object $detalleProducto;
    public object $ventas; //historial de ventas;
    public object $proveedores;
    public bool $modalAgregar = false;
    public bool $modalCategoria = false;
    public bool $modalEditar = false;
    public bool $modalEliminar = false;
    public bool $tableCategoria = false;
    public bool $editar = false;
    public bool $detalles = false;
    public bool $historial = false;
    public bool $modalProveedor = false;
    public bool $flagCodigo = false;
    public bool $alertaDelete = false;
    public $nombre = '';
    public $codigo;
    public $descripcion;
    public $categoria_id;
    public $proveedor_id;
    public $precio;
    public $precio_compra;
    public $stock_actual;
    public $foto;
    public $imagePreview;
    public $precio_interno;
    public $capacidad;
    public $cantidadCapacidad;
    public $usoInterno;
    public string $search = '';

    /**
     * 
     */
    public function mount()
    {
        Helper::check();
        if (empty(session('modulos')['inventario'])) {
            return redirect('/');
        }
        $this->productos = $this->getProductos();
        $this->categorias = Categoria::where('owner_id', $this->ownerId())->get();
        $this->proveedores = Proveedor::where('owner_id', $this->ownerId())->get();
    }

    public function getProductos()
    {
        return Cache::remember('productos', 60, function () {
            return Producto::where('owner_id', $this->ownerId())->get();
        });
    }

    public function forgetProductos()
    {
        Cache::forget('productos');
    }

    public function filtrar()
    {
        $this->productos = Producto::where('owner_id', $this->ownerId())
            ->where(function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('codigo', 'like', '%' . $this->search . '%');
            })
            ->get();
        $this->forgetProductos();
    }

    public function flag()
    {
        $this->search = '';
        $this->mount();
    }
    /**
     * 
     */
    public function ownerId(): mixed
    {
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

    /**
     * 
     */
    public function alertaTrue($productoId): void
    {
        $this->productoId = $productoId;
        $this->alertaDelete = true;
    }
    public function alertaFalse(): void
    {
        $this->alertaDelete = false;
    }

    /*
     * 
     */
    public function proveedorTrue(): void
    {
        $this->modalProveedor = true;
    }
    public function proveedorFalse(): void
    {
        $this->modalProveedor = false;
    }

    public function crearProveedor()
    {
        try {
            $this->validate([
                'nombreP' => 'required',
            ]);

            $this->proveedores = Proveedor::create([
                'nombre' => $this->nombreP,
                'telefono' => $this->telefonoP ?? null,
                'direccion' => $this->direccionP ?? null,
                'email' => $this->email ?? null,
                'ruc' => $this->ruc ?? null,
                'owner_id' => $this->ownerId(),
            ]);
        } catch (\Exception $e) {
            DB::commit();
            return redirect()->route('inventario')->with('error', 'Error al agregar el proveedor ' . $e->getMessage());
        }
        $this->modalProveedor = false;
        return redirect()->route('inventario')->with('agregado', 'Proveedor agregado correctamente');
    }

    /**
     * 
     */
    public function historialTrue(int $productoId): void
    {
        $this->ventas = MovimientoProduct::where('producto_id', $productoId)->get();
        $this->detallesFalse();
        $this->historial = true;
    }
    public function historialFalse($productoId): void
    {
        $this->ventas;
        $this->detallesTrue($productoId);
        $this->historial = false;
    }

    /**
     * 
     */
    public function detallesTrue($productoId): void
    {
        $this->detalleProducto = Producto::where('id', $productoId)
            ->where('owner_id', $this->ownerId())
            ->first();
        $this->detalles = true;
    }
    public function detallesFalse(): void
    {
        $this->detalles = false;
        $this->detalleProducto;
    }

    /**
     * 
     */
    public function editarTrue($productoId): void
    {
        $this->productoToEdit = Producto::where('id', $productoId)
            ->where('owner_id', $this->ownerId())
            ->first();
        $this->editar = true;
    }
    public function editarFalse(): void
    {
        $this->editar = false;
    }

    /**
     * 
     */
    public function openVerTodo($productoId): void
    {
        $this->verTodo = 100;
        $this->productoId = $productoId;
    }
    public function closeVerTodo(): void
    {
        $this->verTodo = 9;
        $this->productoId = '';
    }

    /**
     * 
     */
    public function opneTableCategoria(): void
    {
        $this->tableCategoria = true;
    }
    public function closeTableCategoria(): void
    {
        $this->tableCategoria = false;
    }
    /**
     * 
     */
    public function openModalAgregar(): void
    {
        $this->modalAgregar = true;
    }
    public function closeModalAgregar(): void
    {
        $this->modalAgregar = false;
    }
    /**
     * 
     */
    public function openModalCategoria(): void
    {
        $this->modalCategoria = true;
    }
    public function closeModalCategoria(): void
    {
        $this->modalCategoria = false;
    }

    /**
     * 
     */
    public function agregarCategoria()
    {
        $this->validate([
            'categoria' => 'required'
        ]);
        Categoria::create([
            'nombre' => $this->categoria,
            'owner_id' => $this->ownerId(),
        ]);

        $this->categoria = '';
        $this->closeModalCategoria();
        return redirect()->route('inventario')->with('agregado', 'Categoria agregada correctamente');
    }

    public function deleteProducto(): void
    {
        try {
            $producto = Producto::where('id', $this->productoId)
                ->where('owner_id', $this->ownerId())
                ->first();
            if ($producto->foto) {
                unlink(public_path('uploads/productos/' . $producto->foto));
            }
            $producto->delete();
        } catch (\Exception $e) {
            DB::commit();
            $this->dispatch('producto-noborrado');
            return;
        }

        $this->alertaFalse();
        $this->dispatch('producto-borrado');
        $this->productos = Producto::where('owner_id', $this->ownerId())->get();
        $this->forgetProductos();
        $this->productos = $this->getProductos();
    }

    public function eliminarCategoria($categoriaId)
    {
        try {
            Categoria::where('id', $categoriaId)
                ->where('owner_id', $this->ownerId())
                ->delete();
        } catch (\Exception $e) {
            DB::commit();
            throw new \Exception($e->getMessage());
        }
        return redirect()->route('inventario')->with('eliminado', 'Categoria eliminada correctamente');
    }

    public function updatedFoto()
    {
        if ($this->foto) {
            $this->imagePreview = $this->foto->temporaryUrl();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): void {
        if (Auth::user()->plan_id == 1) {
            if (filled($this->unidades) ||
                filled($this->precio_interno) ||
                filled($this->capacidad) ||
                filled($this->cantidadCapacidad)
                ) {
                $this->dispatch('error', 'Debes actualizar tu plan para usar configuración interna');
            }
        }

        $this->validate([
            'nombre' => 'required',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'codigo' => 'nullable|unique:productos,codigo',
            'descripcion' => 'nullable',
            'categoria_id' => 'required|exists:categorias,id',
            'precio' => 'required',
            'precio_compra' => 'nullable',
            'stock_actual' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'proveedor_id.exists' => 'El proveedor seleccionado no es válido.',
            'codigo.unique' => 'El código del producto ya existe.',
            'categoria_id.required' => 'El campo categoría es obligatorio.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'precio.required' => 'El campo precio es obligatorio.',
            'stock_actual.required' => 'El campo stock actual es obligatorio.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg, webp.',
            'foto.max' => 'La imagen no debe pesar más de 2MB.',
        ]);

        if ($this->precio_interno != null) {
            $this->validate([
                'unidades' => 'required',
                'cantidad' => 'required',
                'capacidad' => 'required',
                'cantidadCapacidad' => 'required',
            ], [
                'unidad_medida.required' => 'El campo unidad de medida es obligatorio.',
                'cantidad.required' => 'El campo cantidad es obligatorio.',
                'capacidad.required' => 'El campo capacidad es obligatorio.',
            ]);
        }
        if ($this->foto) {
            $imageName = time() . '.' . $this->foto->getClientOriginalExtension();
            $this->foto->storeAs('uploads/productos', $imageName, 'public_path');
        }

        $producto = Producto::create([
            'nombre' => $this->nombre,
            'codigo' => $this->flagCodigo ? $this->codigo(6) : ($this->codigo ?? null),
            'proveedor_id' => $this->proveedor_id ?? null,
            'descripcion' => $this->descripcion,
            'categoria_id' => $this->categoria_id,
            'precio' => $this->precio,
            'precio_compra' => $this->precio_compra,
            'stock_actual' => $this->stock_actual,
            'foto' => $imageName ?? null,
            'owner_id' => $this->ownerId(),
            'unidad_medida' => $this->unidades ?? null,
            'cantidad' => $this->cantidad ?? null,
            'precio_interno' => $this->precio_interno ?? null,
            'unidad_capacidad' => $this->capacidad ?? null,
            'cantidad_capacidad' => $this->cantidadCapacidad ?? null,
            'sobrante' => $this->cantidadCapacidad ?? null,
            'solo_uso_interno' => $this->usoInterno ?? false,
        ]);

        if (!$producto) {
            $this->dispatch('error-store');
        }
        $this->forgetProductos();
        $this->productos = $this->getProductos();
        $this->dispatch('success-store');
    }


    /**
     * refresh the data after store
     */
    #[On('success-store')]
    public function refreshSucces()
    {
        $this->productos = Producto::where('owner_id', $this->ownerId())->get();
        $this->nombre = null;
        $this->codigo = null;
        $this->descripcion = null;
        $this->categoria_id = null;
        $this->proveedor_id = null;
        $this->precio = null;
        $this->precio_compra = null;
        $this->stock_actual = null;
        $this->foto = null;
        $this->imagePreview = null;
        $this->precio_interno = null;
        $this->capacidad = null;
        $this->cantidadCapacidad = null;
    }
    /**
     * 
     */
    #[On('error-store')]
    public function updateSuccess()
    {
        $this->productos = Producto::where('owner_id', $this->ownerId())->get();
        //$this->productos = Cache::get('productos_todos');
    }

    #[On('producto-borrado')]
    public function r() {}
    private function codigo($length): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
    public function removeImage()
    {
        $this->imagePreview = null;
        $this->foto = null;
    }
    public function render(): \Illuminate\View\View
    {
        return view('livewire.inventario');
    }
}
