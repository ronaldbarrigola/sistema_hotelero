<?php

namespace App\Repositories\Business;

use Illuminate\Support\Facades\Auth;
use App\Repositories\Base\AgenciaRepository;
use App\Entidades\Business\SerialComprobante;
use App\Repositories\Business\NumerosEnLetras;
use Elibyy\TCPDF\Facades\TCPDF;
use Carbon\Carbon;

class ComprobanteRepository{

    protected $agenciaRep;
    protected $numerosEnLetras;

    public function __construct(AgenciaRepository $agenciaRep,NumerosEnLetras $numerosEnLetras){
        $this->agenciaRep=$agenciaRep;
        $this->numerosEnLetras=$numerosEnLetras;
    }


    public function obtenerSerialComprobante($agencia_id){
        return SerialComprobante::where('agencia_id',$agencia_id)->first();
    }

    public function generarCorrelativoSerialComprobante($agencia_id){
        $serial_comprobante=$this->obtenerSerialComprobante($agencia_id);

       if(is_null($serial_comprobante)){
        $serial_comprobante=new SerialComprobante();
        $serial_comprobante->agencia_id=Auth::user()->agencia_id;
        $serial_comprobante->correlativo=1;
        $serial_comprobante->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $serial_comprobante->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $serial_comprobante->estado=1;
        $serial_comprobante->save();
       } else {
         $serial_comprobante->correlativo=$serial_comprobante->correlativo + 1;
         $serial_comprobante->update();
       }

        return $serial_comprobante->correlativo;
    }

    //Generar comprobante PDF papel carta
    public function comprobanteCompra($compra,$detalle){

        //https://tcpdf.org Manual tcpdf

        //Definir tamaño de la hoja
        $pdf = new TCPDF();

        //Establecer numeros de pagina, se repiten en todas la paginas
        $pdf::setFooterCallback(function ($pdf){
            //Numero de pagina
            $pdf->setY(-10);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->Cell(0, 2, 'Página '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        });

        $pdf::AddPage('P','cm','Letter', true, 'UTF-8', false);   //p: vertical  L:horizontal

        //Imagen Logo
        $pdf::Image('imagenes/logo_empresa.png',10,5,55,30,'PNG');

        // Agregamos los datos de la empresa
        $posY=0;
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(80,$posY+10);
        $pdf::Cell(60,6,"DAVID S.R.L.",0,1,'C');
        $pdf::SetFont('Helvetica','B',10);
        $pdf::SetXY(80,$posY+15);
        $pdf::Cell(60,6,"Venta de material, Deportivo",0,1,'C');
        $pdf::SetXY(80,$posY+20);
        $pdf::Cell(60,6,"Telefono Whatsapp",0,1,'C');
        $pdf::SetFont('Helvetica','',12);

        // Numero de factura
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(170,$posY+15);
        $pdf::Cell(4,6,"Nro. ");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(180,$posY+15);
        $pdf::Cell(20,6,$compra->compra_id,0,1,'L');

        //Titulo del comprobante
        $pdf::ln(30);
        $pdf::SetFont('Helvetica','B',16);
        $pdf::Cell(0,4,"DETALLE DE COMPRA",0,1,'C');

        //Datos del ordenante y la cuenta
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+40);
        $pdf::Cell(5,6,"Fecha :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(38,$posY+40);
        $pdf::Cell(5,6,$compra->fecha);

        //Datos del ordenante y la cuenta
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+45);
        $pdf::Cell(5,6,"Proveedor :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(38,$posY+45);
        $pdf::Cell(5,6,$compra->proveedor);

        /// Apartir de aqui empezamos con la tabla de productos
        $pdf::Ln(15);

        $tbody="";
        $total=0;
        foreach($detalle as $row)
        {
             $tbody=$tbody.'<tr>
                 <td align="center" width="15%">'.$row->codigo1.'</td>
                 <td align="center" width="8%">'.$row->cantidad.'</td>
                 <td align="left" width="47%">'.$row->producto.'</td>
                 <td align="center" width="15%">'.$row->pu_compra.'</td>
                 <td align="center" width="15%">'.number_format($row->total,2,".",",").'</td>
             </tr>';

             $total=$total+$row->total;
        }

        $html= '<table border="1" cellspacing="0" style="text-align:center;">

                     <thead><tr bgcolor="#CCE5FF">
                         <th width="15%"><strong>Codigo</strong></th>
                         <th width="8%"><strong>Cant</strong></th>
                         <th width="47%"><strong>Descripcion del producto</strong></th>
                         <th width="15%"><strong>Precio Unitario</strong></th>
                         <th width="15%"><strong>Total</strong></th>
                     </tr></thead>

                 <tbody>'.$tbody.'</tbody>
                 <tfoot>
                    <tr>
                       <th colspan="4" style="text-align:right;">TOTAL :'.$this->numerosEnLetras->convertir($total,'Bolivianos',true).'</th>
                       <th>'.$total.'</th>
                    </tr>
                 </tfoot>
                 </table>';

        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output(public_path('pdf/compra/'.$compra->compra_id.'.pdf'), 'F');
    }

    //Generar comprobante PDF papel carta
    public function comprobanteVenta($venta,$detalle){

        //https://tcpdf.org Manual tcpdf

        $agencia=$this->agenciaRep->obtenerAgenciaPorId($venta->agencia_id);

        //Definir tamaño de la hoja
        $pdf = new TCPDF();

        //Establecer numeros de pagina, se repiten en todas la paginas
        $pdf::setFooterCallback(function ($pdf){
            //Numero de pagina
            $pdf->setY(-10);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->Cell(0, 2, 'Página '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        });

        $pdf::AddPage('P','cm','Letter', true, 'UTF-8', false);

        //Imagen Logo
        $pdf::Image('imagenes/logo_empresa.png',10,5,50,40,'PNG');

       // Agregamos los datos de la empresa
        $posY=0;
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(80,$posY+10);
        $pdf::Cell(60,6,"DAVID S.R.L.",0,1,'C');
        $pdf::SetFont('Helvetica','B',10);
        $pdf::SetXY(80,$posY+15);
        $pdf::Cell(60,6,"Venta de material, Deportivo",0,1,'C');
        $pdf::SetXY(80,$posY+20);
        $pdf::Cell(60,6,"Telefono Whatsapp",0,1,'C');
        $pdf::SetFont('Helvetica','',12);
        // $pdf::SetXY(80,$posY+25);
        // $pdf::Cell(60,6,$agencia->direccion,0,1,'C');
        // $pdf::SetXY(80,$posY+30);
        // $pdf::Cell(60,6,$agencia->fono,0,1,'C');

         // Numero de factura
         $pdf::SetFont('Helvetica','B',12);
         $pdf::SetXY(170,$posY+15);
         $pdf::Cell(4,6,"Nro. ");
         $pdf::SetFont('Helvetica','',12);
         $pdf::SetXY(180,$posY+15);
         $pdf::Cell(20,6,$venta->nro_venta,0,1,'L');

        //Titulo del comprobante
        $pdf::ln(45);
        $pdf::SetFont('Helvetica','B',16);
        $pdf::Cell(0,4,"DETALLE DE VENTA",0,1,'C');

        //Datos del ordenante y la cuenta
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+50);
        $pdf::Cell(5,6,"Fecha :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(38,$posY+50);
        $pdf::Cell(5,6,$venta->fecha);

         //Datos del ordenante y la cuenta
         $pdf::SetFont('Helvetica','B',12);
         $pdf::SetXY(9,$posY+55);
         $pdf::Cell(5,6,"Cliente :");
         $pdf::SetFont('Helvetica','',12);
         $pdf::SetXY(38,$posY+55);
         $pdf::Cell(5,6,$venta->cliente);

          //Datos del ordenante y la cuenta
          $pdf::SetFont('Helvetica','B',12);
          $pdf::SetXY(9,$posY+60);
          $pdf::Cell(5,6,"Vendido por :");
          $pdf::SetFont('Helvetica','',12);
          $pdf::SetXY(38,$posY+60);
          $pdf::Cell(5,6,$venta->vendedor);


        /// Apartir de aqui empezamos con la tabla de productos
        $pdf::Ln(15);

        $tbody="";
        $total=0;
        foreach($detalle as $row)
        {
            $tbody=$tbody.'<tr>
                <td align="center" width="15%">'.$row->codigo1.'</td>
                <td align="center" width="8%">'.$row->cantidad.'</td>
                <td align="left" width="32%">'.$row->producto.'</td>
                <td align="center" width="15%">'.$row->pu_venta.'</td>
                <td align="center" width="15%">'.$row->descuento.'</td>
                <td align="center" width="15%">'.number_format($row->total,2,".",",").'</td>
            </tr>';

            $total=$total+$row->total;
        }

        $html= '<table border="1" cellspacing="0" style="text-align:center;">

                    <thead><tr bgcolor="#CCE5FF">
                        <th width="15%"><strong>Codigo</strong></th>
                        <th width="8%"><strong>Cant</strong></th>
                        <th width="32%"><strong>Descripcion del producto</strong></th>
                        <th width="15%"><strong>Precio Unitario</strong></th>
                        <th width="15%"><strong>Descuento</strong></th>
                        <th width="15%"><strong>Total</strong></th>
                    </tr></thead>

                <tbody>'.$tbody.'</tbody>
                <tfoot>
                   <tr>
                      <th colspan="5" style="text-align:right;">TOTAL :'.$this->numerosEnLetras->convertir($total,'Bolivianos',true).'</th>
                      <th>'.$total.'</th>
                   </tr>
                </tfoot>
                </table>';

        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output(public_path('pdf/venta/'.$venta->venta_id.'.pdf'), 'F');
    }

    //Generar comprobante PDF papel carta
    public function comprobanteTraspaso($traspaso,$detalle){

        //https://tcpdf.org Manual tcpdf

        //Definir tamaño de la hoja
        $pdf = new TCPDF();

        //Establecer numeros de pagina, se repiten en todas la paginas
        $pdf::setFooterCallback(function ($pdf){
            //Numero de pagina
            $pdf->setY(-15);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->Cell(0, 2, 'Página '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
           // $pdf->ln();
           // $pdf->Cell(0, 2, "Realizado por : Luis Antonio Quispe ", 0, false, 'L', 0, '', 0, false, 'T', 'M');
        });

        $pdf::AddPage('P','cm','Letter', true, 'UTF-8', false);

       // Agregamos los datos DE CABECERA
        $posY=0;
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(80,$posY+10);
        $pdf::Cell(60,6,"DOCUMENTO DE TRASPASO",0,1,'C');

        // Numero de TRASPASO
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(165,$posY+15);
        $pdf::Cell(4,6,"Nro. :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(180,$posY+15);
        $pdf::Cell(20,6,$traspaso->traspaso_id,0,1,'L');

        //Datos del ordenante y la cuenta
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+25);
        $pdf::Cell(5,6,"Agencia Origen :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(50,$posY+25);
        $pdf::Cell(5,6,$traspaso->agencia_origen);

        //Datos del ordenante y la cuenta
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+30);
        $pdf::Cell(5,6,"Agencia Destino :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(50,$posY+30);
        $pdf::Cell(5,6,$traspaso->agencia_destino);

        /// Apartir de aqui empezamos con la tabla de productos
        $pdf::Ln(12);
        $pdf::SetFont('Helvetica','',12);
        $tbody="";
        $cantidad=0;
        foreach($detalle as $row)
        {
            $tbody=$tbody.'<tr>
                <td align="center" width="30%">'.$row->codigo2.'</td>
                <td align="left" width="50%">'.$row->producto.'</td>
                <td align="center" width="20%">'.$row->cantidad.'</td>
            </tr>';
            $cantidad=$cantidad+$row->cantidad;
        }

        $html= '<table border="1" cellspacing="0" style="text-align:center;">
                    <thead><tr bgcolor="#CCE5FF">
                        <th width="30%"><strong>Codigo</strong></th>
                        <th width="50%"><strong>Producto</strong></th>
                        <th width="20%"><strong>Cantidad</strong></th>
                    </tr></thead>
                <tbody>'.$tbody.'</tbody>
                <tfoot>
                    <tr>
                    <th colspan="2"  style="text-align:right;">CANTIDAD TOTAL </th>
                    <th>'.$cantidad.'</th>
                    </tr>
                </tfoot>
                </table>';

        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output(public_path('pdf/traspaso/'.$traspaso->traspaso_id.'.pdf'), 'F');
    }

}//fin clase
