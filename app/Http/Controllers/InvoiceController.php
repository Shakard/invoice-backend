<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Notifications\InvoiceSent;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use SoapFault;

class InvoiceController extends Controller
{
    public function getNextInvoice()
    {
        $secuencial = $this->getSecuencial();

        return response()->json([
            'summary' => 'success',
            'code' => '201',
            'data' => $secuencial,
        ], 201);
    }
    public function store($result, $accessKey, Request $request)
    {
        $notify = new SendInvoiceMailController(); 
        $secuencial = $this->getSecuencial();       
        $invoice = new Invoice();
        $invoice->clave_acceso = $accessKey;
        $invoice->codigo_establecimiento = '001';
        $invoice->punto_emision = '003';
        $invoice->secuencial = $secuencial;
        // $invoice->numero_autorizacion = $request->input('  ');
        $invoice->ruc_emisor = $request->input('invoice.userRuc');
        $invoice->nombre_comercial_emisor = $request->input('invoice.clientName');
        $invoice->razon_social_emisor = $request->input('invoice.clientName');
        $invoice->ambiente = '1';
        $invoice->estado = '02';
        // $invoice->estado = $result->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado;
        // $invoice->mensaje = $request->input('  code');
        $invoice->fecha_emision = date('d-m-y');;
        // $invoice->fecha_autorizacion = $request->input('  code');
        $invoice->valor_total_factura = 600.00;
        // $invoice->tipo_comprobante_id = $request->input('  code');
        // $invoice->archivo_respuesta_sri = $request->input('  code');
        // $invoice->comprobante_firmado = $request->input('  code');
        // $invoice->notificado_correo = $request->input('  code');
        // $invoice->visto_emisor = $request->input('  code');
        // $invoice->tipo_pago = $request->input('  code');
        // $invoice->numer_documento_transferencia = $request->input('  code');

        // $data->table()->associate($table);
        $invoice->save();
        $createPDf = new PDFController();   
        $createPDf->generatePDF($request, $secuencial, $accessKey); 
        $clientMail = $request->input('invoice.clientMail');


        if ($result->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
            // $notify->sendInvitation($clientMail, $accessKey);           
            return response()->json($result, 200);
        } else {
            return response()->json($result, 400);
        }
    }

    public function accessKey($invoiceDate, $invoiceType, $rucNumber, $ambientType, $serialNumber, $invoiceNumber, $codeNumber, $tipoEmision)
    {  
        $code = $invoiceDate . $invoiceType . $rucNumber . $ambientType . $serialNumber . $invoiceNumber . $codeNumber . $tipoEmision;
        $arrayCode = str_split($code);
        $longCode = strlen($code);
        $mult = 2;
        $sum = 0;

        for ($i = $longCode; $i > 0; $i--) {
            $sum += $arrayCode[$i - 1] * $mult;
            $mult++;
            if ($mult > 7) {
                $mult = 2;
            }
        }

        $mod = $sum % 11;
        $res = 11 - $mod;
        if ($res == 11) {
            $res = 0;
        }
        if ($res == 10) {
            $res = 1;
        }

        $accessKey = $code . $res;
        return  $accessKey;
    }

    public function getSecuencial()
    {
        $lastInvoice = Invoice::select('secuencial')->orderBy('created_at', 'desc')->first();
        if ($lastInvoice==null) {
            $secuencial = "000000065";
        } else {
            $array = str_split($lastInvoice->secuencial, 1);
            for ($i = 0; $i < 9; $i++) {
                if ($array[$i] == 0) {
                    unset($array[$i]);
                } else break;
            }
            $lastNumberString = implode("", $array);
            $lastNumber = (int) $lastNumberString;
            $secuencialSolo = $lastNumber + 1;
            $secuencial = str_pad($secuencialSolo, 9, 0, STR_PAD_LEFT);
        }

        return $secuencial;
    }

    public function createXml(Request $request)
    {
        $secuencial = $this->getSecuencial();
        $clientName = $request->input('invoice.clientName');
        $userName = $request->input('invoice.userName');
        $ruc = $request->input('invoice.userRuc');
        $clientMail = $request->input('invoice.clientMail');

        //DATOS NECESARIOS PARA EL ACCESSKEY
        $fechaEmicion = $request->input('invoice.fechaEmicion'); //8    DDMMAAAA
        $codDoc = '01';                                          //2    TABLA 3              
        $ruc = $request->input('invoice.userRuc');               //13
        $ambientType = '1';                                      //1    TABLA 4   
        $establecimiento = '001';
        $puntoEmision = '003';
        $serialNumber = $establecimiento . $puntoEmision;          //6    NUMERO DE SERIE           
        $secuencial = $secuencial;                            //9    NUMERO SECUENCIAL 9 CARACTERES
        $codeNumber = random_int(0, 99999999);
        $codeNumber = str_pad($codeNumber, 8, 0, STR_PAD_LEFT);//8    SOLO UN NUMERO OF 8 CARACTERES    
        $tipoEmision = '1';                                      //1  

        $formatedDate = str_replace('/', '', $fechaEmicion);
        $accessKey = $this->accessKey($formatedDate, $codDoc, $ruc, $ambientType, $serialNumber, $secuencial, $codeNumber, $tipoEmision);


        header('Content-Type: text/html; charset=UTF-8');

        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->preserveWhiteSpace = false;

        $Factura = $xml->createElement('factura');
        $facturaAtributeId = $xml->createAttribute('id');
        $facturaAtributeId->value = 'comprobante';
        $facturaAtributeVersion = $xml->createAttribute('version');
        $facturaAtributeVersion->value = '1.0.0';
        $Factura->appendChild($facturaAtributeId);
        $Factura->appendChild($facturaAtributeVersion);
        $Factura = $xml->appendChild($Factura);


        // INFORMACION TRIBUTARIA.
        $infoTributaria = $xml->createElement('infoTributaria');
        $infoTributaria = $Factura->appendChild($infoTributaria);
        $cbc = $xml->createElement('ambiente', $ambientType); //
        $cbc = $infoTributaria->appendChild($cbc);
        $cbc = $xml->createElement('tipoEmision', $tipoEmision); //
        $cbc = $infoTributaria->appendChild($cbc);
        $cbc = $xml->createElement('razonSocial', $userName); //
        $cbc = $infoTributaria->appendChild($cbc);
        // $cbc = $xml->createElement('nombreComercial', '1');
        // $cbc = $infoTributaria->appendChild($cbc);
        $cbc = $xml->createElement('ruc', $ruc); //
        $cbc = $infoTributaria->appendChild($cbc);
        $cbc = $xml->createElement('claveAcceso', $accessKey); //
        $cbc = $infoTributaria->appendChild($cbc);
        $cbc = $xml->createElement('codDoc', $codDoc); //viene de tipo de documento. factura, nota de credito, nota de debito
        $cbc = $infoTributaria->appendChild($cbc);
        $cbc = $xml->createElement('estab', $establecimiento); //
        $cbc = $infoTributaria->appendChild($cbc);
        $cbc = $xml->createElement('ptoEmi', $puntoEmision); //
        $cbc = $infoTributaria->appendChild($cbc);
        $cbc = $xml->createElement('secuencial', $secuencial); //
        $cbc = $infoTributaria->appendChild($cbc);
        $cbc = $xml->createElement('dirMatriz', 'Barrio: RUMIHURCO Calle: CARCHI Número: 33 Intersección: INTEROCEANICA'); //
        $cbc = $infoTributaria->appendChild($cbc);

        // INFORMACIOO DE FACTURA.
        $infoFactura = $xml->createElement('infoFactura');
        $infoFactura = $Factura->appendChild($infoFactura);
        $cbc = $xml->createElement('fechaEmision', $fechaEmicion); //dd/mm/aa
        $cbc = $infoFactura->appendChild($cbc);
        $cbc = $xml->createElement('dirEstablecimiento', 'Barrio: RUMIHURCO Calle: CARCHI Número: 33 Intersección: INTEROCEANICA'); //
        $cbc = $infoFactura->appendChild($cbc);
        // $cbc = $xml->createElement('contribuyenteEspecial', '000');
        // $cbc = $infoFactura->appendChild($cbc);
        $cbc = $xml->createElement('obligadoContabilidad', 'NO');
        $cbc = $infoFactura->appendChild($cbc);
        $cbc = $xml->createElement('tipoIdentificacionComprador', '05'); // ruc, cedula, pasaporte, etc.
        $cbc = $infoFactura->appendChild($cbc);
        $cbc = $xml->createElement('razonSocialComprador', $clientName);
        $cbc = $infoFactura->appendChild($cbc);
        $cbc = $xml->createElement('identificacionComprador', '1725681462');
        $cbc = $infoFactura->appendChild($cbc);
        $cbc = $xml->createElement('totalSinImpuestos', $request->invoice['subTotal']);
        $cbc = $infoFactura->appendChild($cbc);
        $cbc = $xml->createElement('totalDescuento', '0.00');
        $cbc = $infoFactura->appendChild($cbc);


        $totalConImpuestos = $xml->createElement('totalConImpuestos');
        $totalConImpuestos = $infoFactura->appendChild($totalConImpuestos);
        $totalImpuesto = $xml->createElement('totalImpuesto');
        $totalImpuesto = $totalConImpuestos->appendChild($totalImpuesto);
        $cbc = $xml->createElement('codigo', '2');
        $cbc = $totalImpuesto->appendChild($cbc);
        $cbc = $xml->createElement('codigoPorcentaje', '0');
        $cbc = $totalImpuesto->appendChild($cbc);
        // $cbc = $xml->createElement('descuentoAdicional', '001');
        // $cbc = $totalImpuesto->appendChild($cbc);
        $cbc = $xml->createElement('baseImponible', $request->invoice['subTotal']);
        $cbc = $totalImpuesto->appendChild($cbc);
        $cbc = $xml->createElement('valor', '0.00');
        $cbc = $totalImpuesto->appendChild($cbc);

        $cbc = $xml->createElement('propina', '0');
        $cbc = $infoFactura->appendChild($cbc);
        $cbc = $xml->createElement('importeTotal', $request->invoice['subTotal']);
        $cbc = $infoFactura->appendChild($cbc);
        $cbc = $xml->createElement('moneda', 'DOLAR');
        $cbc = $infoFactura->appendChild($cbc);

        //FORMPA DE PAGO
        $pagos = $xml->createElement('pagos');
        $pagos = $infoFactura->appendChild($pagos);
        $pago = $xml->createElement('pago');
        $pago = $pagos->appendChild($pago);
        $cbc = $xml->createElement('formaPago', '16');
        $cbc = $pago->appendChild($cbc);
        $cbc = $xml->createElement('total', $request->invoice['subTotal']);
        $cbc = $pago->appendChild($cbc);

        //DETALLES DE LA FACTURA.
        $detalles = $xml->createElement('detalles');
        $detalles = $Factura->appendChild($detalles);

        $descripcion = '';
        $i = 0;

        // EMULANDO LA CONSULTA A LA BASE DE DATOS DE UN SELECT
        // $lineas = array(

        //     "1" => array(
        //         "descripcion" => "SERVICIOS PROFECIONALES PRESTADOS",
        //         "cantidad" => "1.0",
        //         "precioUnitario" => "600.00"
        //     )
        //     // "2" => array(
        //     //     "descripcion" => "descricon del producto 2",
        //     //     "precioUnitario" => "100",
        //     //     "cantidad" => "12"
        //     // )
        // );

        $lineas = $request->invoice['details'];

        foreach ($lineas as $d) {
            $i++;
            $numerolinea = $i;

            $detalle = $xml->createElement('detalle');
            $detalle = $detalles->appendChild($detalle);
            $cbc = $xml->createElement('codigoPrincipal', '00'. $i);
            $cbc = $detalle->appendChild($cbc);
            // $cbc = $xml->createElement('codigoAuxiliar', '1');
            // $cbc = $detalle->appendChild($cbc);
            $cbc = $xml->createElement('descripcion', $d["item"]);
            $cbc = $detalle->appendChild($cbc);
            $cbc = $xml->createElement('cantidad', $d["quantity"]);
            $cbc = $detalle->appendChild($cbc);
            $cbc = $xml->createElement('precioUnitario', $d["price"]);
            $cbc = $detalle->appendChild($cbc);
            $cbc = $xml->createElement('descuento', '0.00');
            $cbc = $detalle->appendChild($cbc);
            $cbc = $xml->createElement('precioTotalSinImpuesto', $d["amount"]);
            $cbc = $detalle->appendChild($cbc);

            $impuestos = $xml->createElement('impuestos');
            $impuestos = $detalle->appendChild($impuestos);
            $impuesto = $xml->createElement('impuesto');
            $impuesto = $impuestos->appendChild($impuesto);
            $cbc = $xml->createElement('codigo', '2');
            $cbc = $impuesto->appendChild($cbc);
            $cbc = $xml->createElement('codigoPorcentaje', '0');
            $cbc = $impuesto->appendChild($cbc);
            $cbc = $xml->createElement('tarifa', '0.0');
            $cbc = $impuesto->appendChild($cbc);
            $cbc = $xml->createElement('baseImponible', $d["amount"]);
            $cbc = $impuesto->appendChild($cbc);
            $cbc = $xml->createElement('valor', '0.00');
            $cbc = $impuesto->appendChild($cbc);
        }

        // //INFORMACION ADICIONAL
        $infoAdicional = $xml->createElement('infoAdicional');
        $infoAdicional = $Factura->appendChild($infoAdicional);
        $campoAdicional = $xml->createElement('campoAdicional', $clientMail); //
        $facturaAtributeVersion = $xml->createAttribute('nombre');
        $facturaAtributeVersion->value = 'Email';
        $campoAdicional->appendChild($facturaAtributeVersion);
        $cbc = $infoAdicional->appendChild($campoAdicional);




        $xml->formatOutput = true;
        $strings_xml       = $xml->saveXML();

        $xml->save('./generated/' . $accessKey . '.xml');
        return $this->signXmltest($accessKey, $request);
        // return response()->json($strings_xml, 200);
      
    }

    public function signXmltest($accessKey, Request $request)
    {
        $signerPath = "./signer/QuijoteLuiFirmador-2.4.jar ";
        $signKey = "./signKey/5796837_identity.p12 ";
        // exec("java -jar C:\Workspace\QuijoteLuiFirmador\dist\QuijoteLuiFirmador-2.4.jar " . $accessKey . ".xml " . "C:/xampp/htdocs/invoice-backend/public/5796837_identity.p12 cualquierC0sa*", $e);
        exec("java -jar ". $signerPath . $accessKey . ".xml " . $signKey . "cualquierC0sa*", $e);

        // echo json_encode($e, JSON_PRETTY_PRINT);

        sleep(2);

        return $this->sendInvoiceToSRI($accessKey, $request);

    }



    public function sendInvoiceToSRI($accessKey, Request $request)
    {
        $opts = array(
            'http' => array(
                'user_agent' => 'PHPSoapClient'
            )
        );
        $context = stream_context_create($opts);

        $soapClientOptions = array(
            'stream_context' => $context,
            'cache_wsdl' => WSDL_CACHE_NONE
        );
        $webReception = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl';

        $archivoXML = file_get_contents('./signed/' . $accessKey . '.xml');
        $parameters = array("xml" => $archivoXML);


        try {
            $webServiceReception = new \SoapClient($webReception, $soapClientOptions);
            $result = $webServiceReception->validarComprobante($parameters);

        } catch (SoapFault $e) {
            echo $e->getMessage();
        }
        sleep(3);
        // return response()->json($result, 200);

        return $this->verifyInvoice($accessKey, $request);      
    }

    public function verifyInvoice($accessKey, Request $request)
    {
        $opts = array(
            'http' => array(
                'user_agent' => 'PHPSoapClient'
            )
        );
        $context = stream_context_create($opts);

        $soapClientOptions = array(
            'stream_context' => $context,
            'cache_wsdl' => WSDL_CACHE_NONE
        );
        $webVerify = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';
        $verifyParameters = array("claveAccesoComprobante" => $accessKey);
        try {
            $webServiceVerify = new \SoapClient($webVerify, $soapClientOptions);
            $result = $webServiceVerify->autorizacionComprobante($verifyParameters);
            // print_r($result);
        } catch (SoapFault $e) {
            // echo $e->getMessage();
        }
        // return response()->json($result, 200);

        if ($result->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO') {
            file_put_contents('./authorized/' . $accessKey . '.xml', $result->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante);
            $this->store($result, $accessKey, $request);
            return response()->json($result, 200);
        } else {
            return response()->json($result, 400);
        }
    }  
}
