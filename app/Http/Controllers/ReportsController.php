<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\MovimientoProduct;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller {
    public function exportarPdf(Request $request) { 
        if(Auth::user()->plan_id == 1 || Auth::user()->plan_id == 2){
            return back()->with('error', 'No tienes permisos para generar reportes');

        }           
        if($request->filtroPor == 1){        
            $desde = $request->desde . ' 00:00:00' ?? now()->startOfDay();
            $hasta = $request->hasta . ' 23:59:59' ?? now()->endOfDay();

            $ventas = MovimientoProduct::join('productos', 'mivimiento_products.producto_id', '=', 'productos.id')                                       
                                            ->whereBetween('mivimiento_products.created_at', [$desde, $hasta])
                                            ->whereNotNull('mivimiento_products.producto_id')                                        
                                            ->orderBy('productos.ventas', 'desc')
                                            ->get();        
        }else{                    
            $desde = empty($request->desde) ? now()->startOfDay()->format('Y-m-d H:s:i') : Carbon::parse($request->desde)->startOfDay()->format('Y-m-d H:s:i'); 
            $hasta = empty($request->hasta) ? now()->endOfDay()->format('Y-m-d H:s:i') :  Carbon::parse($request->hasta)->endOfDay()->format('Y-m-d H:s:i');            
            $ventas = MovimientoProduct::join('tipo_consultas', 'mivimiento_products.consulta_id', '=', 'tipo_consultas.id')
                                            ->whereBetween('mivimiento_products.created_at', [$desde, $hasta])
                                            ->whereNotNull('mivimiento_products.consulta_id')
                                            ->orderBy('tipo_consultas.veces_realizadas', 'desc')
                                            ->get();
                                        
        }            
        $data = [
            'ventas' => $ventas,
            'title' => 'Reporte de Ventas',
            'desde' => $request->desde,
            'hasta' => $request->hasta,
            'filtroPor' => $request->filtroPor,
        ];            
        $pdf = Pdf::loadView('reportes.ventas', $data);        
        return $pdf->download('reporte_ventas.pdf');
    }


    public function reporteEntradas(Request $request) {     
        if(Auth::user()->plan_id == 1 || Auth::user()->plan_id == 2){
            return back()->with('error', 'No tienes permisos para generar reportes');
        }   
        $desde = $request->desde != null ? $request->desde . ' 00:00:00' : now()->startOfDay();
        $hasta = $request->desde != null ? $request->hasta . ' 23:59:59' : now()->endOfDay();
        
        $ventas = Movimiento::where('created_at','>=', $desde )
                            ->where('created_at', '<=', $hasta)
                            ->get();                
        $data = [
            'ventas' => $ventas,
            'movimientoP' => MovimientoProduct::all(),
            'title' => 'Reporte de Entradas',
            'desde' => $desde,
            'hasta' => $hasta
        ];          
                
        
        $pdf = Pdf::loadView('reportes.entradas', $data);        
        return $pdf->download('reporte_entradas.pdf');
    }
}
