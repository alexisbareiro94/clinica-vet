<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Producto;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;

class InventarioController extends Controller
{
    public function update(Request $request, $productoId): RedirectResponse {
        try {            
            if (Auth::user()->plan_id == 1) {
                if (
                    filled($request->unidad_medida) ||
                    filled($request->cantidad) ||
                    filled($request->precio_interno) ||
                    filled($request->cantidad_capacidad)
                ) {
                    return redirect()->back()->with('error', 'Debes actualizar tu plan para usar configuración interna');
                }
            }

            $request->validate([
                'nombre' => 'required',
                'descripcion' => 'nullable',
                'categoria' => 'required|exists:categorias,id',
                'precio' => 'required',
                'precio_compra' => 'nullable',
                'stock_actual' => 'required',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            $producto = Producto::where('id', $productoId)
                ->where('owner_id', Helper::ownerId())
                ->firstOrFail();

            if ($request->hasFile('foto')) {
                $image_path = $request->file('foto');
                $imageName = time() . $image_path->getClientOriginalExtension();
                $destinationPath = public_path('uploads/productos');
                $image_path->move($destinationPath, $imageName);
            }
            if (isset($request->deleteFoto) && $producto->foto) {
                $rutaFoto = public_path('uploads/productos/' . $producto->foto);

                if (file_exists($rutaFoto)) {
                    unlink($rutaFoto);
                }
            }

            $producto->update([
                'nombre' => $request->nombre ?? $producto->nombre,
                'descripcion' => $request->descripcion ?? $producto->descripcion,
                'categoria_id' => $request->categoria ?? $producto->categoria_id,
                'precio' => $request->precio ?? $producto->precio,
                'precio_compra' => $request->precio_compra ?? $producto->precio_compra,
                'stock_actual' => $request->stock_actual ?? $producto->stock_actual,
                'foto' => $imageName ?? (isset($request->deleteFoto) ? null : $producto->foto),
                'unidad_medida' => $request->unidades ?? $producto->unidad_medida,
                'cantidad' => $request->cantidad ?? $producto->cantidad,
                'precio_interno' => $request->precio_interno ?? $producto->precio_interno,
                'unidad_capacidad' => $request->capacidad ?? $producto->unidad_capacidad,
                'cantidad_capacidad' => $request->cantidadCapacidad ?? $producto->cantidad_capacidad,
            ]);
            $ownerId = Helper::ownerId();
            Helper::forgetProductos();
            Helper::getProductos($ownerId);
        } catch (\Exception $e) {
            return redirect()->route('inventario')->with('error', $e->getMessage());
        }

        return back()->with('editado', 'Producto Actualizado');
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
}
