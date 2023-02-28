<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use PDF;
class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function generatePDF(Request $request, $secuencial, $accessKey)
    {
        $clientName = $request->input('invoice.clientName');
        $clientAddress = $request->input('invoice.clientAddress');
        $userName = $request->input('invoice.userName');
        $ruc = $request->input('invoice.userRuc');
        $clientMail = $request->input('invoice.clientMail');
        $fechaEmicion = $request->input('invoice.fechaEmicion'); //8    DDMMAAAA
        $subtotal = $request->invoice['subTotal'];  
        $tax = $request->invoice['tax'];
        $total = $request->invoice['total'];
        $details = $request->invoice['details'];   
        // foreach ($lineas as $d) {
        //     $i++;

        //     $detalle = $xml->createElement('detalle');
        //     $detalle = $detalles->appendChild($detalle);
        //     $cbc = $xml->createElement('codigoPrincipal', '00'. $i);
        //     $cbc = $detalle->appendChild($cbc);
        //     // $cbc = $xml->createElement('codigoAuxiliar', '1');
        //     // $cbc = $detalle->appendChild($cbc);
        //     $cbc = $xml->createElement('descripcion', $d["item"]);
        //     $cbc = $detalle->appendChild($cbc);
        //     $cbc = $xml->createElement('cantidad', $d["quantity"]);
        //     $cbc = $detalle->appendChild($cbc);
        //     $cbc = $xml->createElement('precioUnitario', $d["price"]);
        //     $cbc = $detalle->appendChild($cbc);
        //     $cbc = $xml->createElement('descuento', '0.00');
        //     $cbc = $detalle->appendChild($cbc);
        //     $cbc = $xml->createElement('precioTotalSinImpuesto', $d["amount"]);
        //     $cbc = $detalle->appendChild($cbc);

        //     $impuestos = $xml->createElement('impuestos');
        //     $impuestos = $detalle->appendChild($impuestos);
        //     $impuesto = $xml->createElement('impuesto');
        //     $impuesto = $impuestos->appendChild($impuesto);
        //     $cbc = $xml->createElement('codigo', '2');
        //     $cbc = $impuesto->appendChild($cbc);
        //     $cbc = $xml->createElement('codigoPorcentaje', '0');
        //     $cbc = $impuesto->appendChild($cbc);
        //     $cbc = $xml->createElement('tarifa', '0.0');
        //     $cbc = $impuesto->appendChild($cbc);
        //     $cbc = $xml->createElement('baseImponible', $d["amount"]);
        //     $cbc = $impuesto->appendChild($cbc);
        //     $cbc = $xml->createElement('valor', '0.00');
        //     $cbc = $impuesto->appendChild($cbc);
        // }

        $data = [
            'invoiceNumber' => $secuencial,
            'clientName' => $clientName,
            'clientId' => '1700000000',
            'clientPhone' => '0953762485',
            'clientAddress' => $clientAddress,
            'clientZipCode' => '000000',  
            'date' => $fechaEmicion,
            'mail' => $clientMail,
            'userName' => $userName,
            'ruc' => $ruc,
            'userPhone' => '0999869607',
            'userAddress' => 'Rumihuaico, carchi 5 y Av.Interoceánica', 
            'city' => 'Quito', 
            'country' => 'Ecuador', 
            'userZipCode' => '170902',  
            // 'title' => 'Welcome to LaravelTuts.com',
            // 'date' => date('m/d/Y'),
            'details' => $details,
            'subTotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'notes' => 'lorem ipsum dolor sit amet, consectetur adipis',
            'footNote' => 'lorem ipsum dolor sit amet, consectetur adipis',
        ]; 
            
        $pdf = PDF::loadView('myPDF', $data);
        $path = './authorizedPDF/';
        $pdf->save($path  . $accessKey . '.pdf');

        // return $pdf->download('laraveltuts.pdf');
    }
}