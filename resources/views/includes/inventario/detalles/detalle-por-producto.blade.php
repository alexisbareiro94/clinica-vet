<div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $detalleProducto->nombre }}</h3>
    
    <div class="space-y-2">
        <div class="flex justify-between">
            <span class="text-gray-600 font-medium">Descripción:</span>
            <p class="text-gray-800 text-right max-w-[80%]">
                {!! nl2br(e($detalleProducto->descripcion)) !!}
            </p>
        </div>
        
        <div class="flex justify-between">
            <span class="text-gray-600 font-medium">Categoría:</span>
            <span class="text-gray-800">{{ $detalleProducto->tipoCategoria->nombre }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-600 font-medium">Precio de compra:</span>
            <span class="text-gray-800">{{ App\Helpers\Helper::formatearMonto($detalleProducto->precio_compra) }} Gs.</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-600 font-medium">Precio de venta:</span>
            <span class="text-gray-800 font-semibold text-lg">
                {{ App\Helpers\Helper::formatearMonto($detalleProducto->precio) }} Gs.
            </span>
        </div>
        @if (Auth::user()->plan_id != 1)
        <div class="flex justify-between">
            <span class="text-gray-600 font-medium">Precio de uso interno, en {{ $detalleProducto->unidad_medida }}:</span>
            <span class="text-gray-800 font-semibold text-lg">
                {{ App\Helpers\Helper::formatearMonto($detalleProducto->precio_interno) }} Gs.
            </span>
        </div>
        
        <div class="flex justify-between">
            <span class="text-gray-600 font-medium">Cantidad por caja, en {{ $detalleProducto->unidad_capacidad }}:</span>
            <span class="text-gray-800">{{ $detalleProducto->cantidad_capacidad }}</span>
        </div>
        @endif

        
        <div class="flex justify-between">
            <span class="text-gray-600 font-medium">Stock disponible:</span>
            <span class="text-gray-800">{{ $detalleProducto->stock_actual }}</span>
        </div>
        
        @if (Auth::user()->plan_id != 1)          
        <div class="flex justify-between">
            <span class="text-gray-600 font-medium">Stock en uso:</span>
            <span class="text-gray-800">{{ $detalleProducto->usoInterno->cantidad ?? ''}}</span>
        </div>
        
        <div class="flex justify-between">
            <span class="text-gray-600 font-medium">Stock Total:</span>
            <span class="text-gray-800">{{ isset($detalleProducto->usoInterno->cantidad)  ?  + $detalleProducto->usoInterno->cantidad +  $detalleProducto->stock_actual  : $detalleProducto->stock_actual}}</span>
        </div>
        @endif

        <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
            <span class="text-gray-600 font-medium">Creado:</span>
            <span class="text-gray-500 text-sm">{{ $detalleProducto->created_at->format('d/m/Y') }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-600 font-medium">Último Uso:</span>
            <span class="text-gray-500 text-sm">{{ isset($detalleProducto->usoInterno->created_at) ? $detalleProducto->usoInterno->created_at->format('d/m/Y H:i') : '' }}</span>
        </div>

        @if (Auth::user()->plan_id != 1)     
         <div class="flex justify-between">
            <span class="text-gray-600 font-medium">Sobrante:</span>
            <span class="text-gray-500 text-sm">{{ isset($detalleProducto->usoInterno) ? ($detalleProducto->usoInterno->cantidad == 1  ? $detalleProducto->sobrante : 'SIN USO') : 'SIN USO'}}</span>
        </div>
        @endif
    </div>
</div>