<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Repositories\Base\AgenciaRepository;
use App\Repositories\Business\NumerosEnLetras;
use Elibyy\TCPDF\Facades\TCPDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use DB;

class ComprobanteRepository{

    protected $agenciaRep;
    protected $numerosEnLetras;

    public function __construct(AgenciaRepository $agenciaRep,NumerosEnLetras $numerosEnLetras){
        $this->agenciaRep=$agenciaRep;
        $this->numerosEnLetras=$numerosEnLetras;
    }

    //Generar comprobante PDF papel carta
     public function comprobante_pago($pago,$detalle){

        //https://tcpdf.org Manual tcpdf

        //$agencia=$this->agenciaRep->obtenerAgenciaPorId($venta->agencia_id);

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
         $pdf::Image('imagenes/logo_empresa.png',15,5,40,40,'PNG'); //(X,Y,Ancho,Alto)

       // Agregamos los datos de la empresa
        $posY=0;
        $pdf::SetFont('Helvetica','B',15);
        $pdf::SetXY(80,$posY+10);
        $pdf::Cell(60,6,"HOTEL WENDYMAR",0,1,'C');
        $pdf::SetFont('Helvetica','',10);
        $pdf::SetXY(80,$posY+15);
        $pdf::Cell(60,6,"Direccion ..",0,1,'C');
        $pdf::SetXY(80,$posY+20);
        $pdf::Cell(60,6,"(Zona ) - Telf. 00000000",0,1,'C');
        $pdf::SetXY(80,$posY+25);
        $pdf::Cell(60,6,"LA PAZ - BOLIVIA",0,1,'C');

         // Numero de pago
         $pdf::SetFont('Helvetica','B',12);
         $pdf::SetXY(170,$posY+15);
         $pdf::Cell(4,6,"Nro. ");
         $pdf::SetFont('Helvetica','',12);
         $pdf::SetXY(180,$posY+15);
         $pdf::Cell(20,6,$pago->pago_id,0,1,'L');

        //Datos de fecha
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+50);
        $pdf::Cell(5,6,"Fecha :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(35,$posY+50);
        $pdf::Cell(5,6,$pago->fecha);

        //Datos del cliente
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+55);
        $pdf::Cell(5,6,"Proveedor :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(35,$posY+55);
        $pdf::Cell(5,6,$compra->proveedor);

         //Titulo del comprobante
         $pdf::ln(10);
         $pdf::SetFont('Helvetica','B',16);
         $pdf::Cell(0,4,"DETALLE DE PAGO",0,1,'C');

        /// Apartir de aqui empezamos con la tabla de productos
        $pdf::ln(15);

        $tbody="";
        $total=0;
        foreach($detalle as $row)
        {
            $tbody=$tbody.'<tr>
                <td align="center" width="8%">'.$row->cantidad.'</td>
                <td align="left" width="37%">'.$row->producto.'</td>
                <td align="left" width="18%">'.$row->precio_unidad.'</td>
                <td align="center" width="10%">'.$row->pu_compra.'</td>
                <td align="center" width="12%">'.number_format($row->total,2,".",",").'</td>
            </tr>';

            $total=$total+$row->total;
        }

        $html= '<table border="1" cellspacing="0" style="text-align:center;">

                    <thead><tr bgcolor="#58D68D">
                        <th width="8%"><strong>Cant</strong></th>
                        <th width="15%"><strong>Cod. Interno</strong></th>
                        <th width="37%"><strong>Descripcion Producto</strong></th>
                        <th width="18%"><strong>Fabricante</strong></th>
                        <th width="10%"><strong>Precio Compra</strong></th>
                        <th width="12%"><strong>Total</strong></th>
                    </tr></thead>

                <tbody>'.$tbody.'</tbody>
                <tfoot>
                   <tr>
                      <th colspan="5" style="text-align:right;">TOTAL :'.$this->numerosEnLetras->convertir($total,'Bolivianos',true).'</th>
                      <th>'.number_format($total,2,".",",").'</th>
                   </tr>
                </tfoot>
                </table>';

        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf::Output(public_path('pdf/comprobante/compra/'.$compra->compra_id.'.pdf'), 'F');
    }

    public function comprobante_pago_prueba($pago,$detalle){

        //https://tcpdf.org Manual tcpdf (mas moderno que fpdf)
        //http://www.fpdf.org/  hereda de fpdf

        $agencia=$this->agenciaRep->obtenerAgenciaPorId($pago->agencia_id);

        //Definir tamaño de la hoja
        $pdf = new TCPDF();
        $pdf::AddPage('P','cm','Letter', true, 'UTF-8', false);

        //BEGIN: Generar marca de agua
        $pdf::StartTransform();
        $pdf::SetTextColor(230, 230, 250); //Color Azul
        $pdf::Rotate(45,65,150); //ideal
        for ($i = 1; $i <= 50; $i++) {
           $pdf::Text($i*5,$i*5,"hotel wendimar  hotel wendimar  hotel wendimar  hotel wendimar  hotel wendimar  hotel wendimar");
        }
        $pdf::StopTransform();
        //END: Generar marca de agua

        //LOGO DE LA EMPRESA
        $pdf::Image('imagenes/logo_empresa.png' , 10 ,5, 50 , 22,'PNG');

        $pdf::SetTextColor(0, 0, 0); //Establecer color de texto Negro

        //DATOS DE LA EMPRESA
        $posY=0;

        //Datos generales
        $pdf::SetFont('Helvetica','B',20);
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(80,$posY+10);
        $pdf::Cell(60,6,$agencia->direccion,0,1,'C');
        $pdf::SetXY(80,$posY+15);
        $pdf::Cell(60,6,$agencia->fono,0,1,'C');
        $pdf::SetXY(80,$posY+20);
        $pdf::Cell(60,6,$agencia->observacion,0,1,'C');

         // Numero de comprobante
         $pdf::SetFont('Helvetica','B',12);
         $pdf::SetXY(170,$posY+12);
         $pdf::Cell(4,6,"Nro. ");
         $pdf::SetFont('Helvetica','',12);
         $pdf::SetXY(180,$posY+12);
         $pdf::Cell(20,6,$pago->transaccion_pago_id,0,1,'L');

        //Titulo del comprobante
        $pdf::ln(10);
        $pdf::SetFont('Helvetica','B',16);
        $pdf::Cell(0,4,"COMPROBANTE DE PAGO",0,1,'C');


        //Datos del ordenante y la cuenta
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+35);
        $pdf::Cell(5,6,"Fecha :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(35,$posY+35);
        $pdf::Cell(5,6,$transaccion->fecha);

        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+40);
        $pdf::Cell(5,6,"Ordenante :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(35,$posY +40);
        $pdf::Cell(5,6,$transaccion->cliente_ordenante);

        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+45);
        $pdf::Cell(5,6,"Cuenta :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(35,$posY+45);

        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+50);
        $pdf::Cell(5,6,"Destino :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(35,$posY+50);
        $pdf::Cell(5,6,$transaccion->pais.' '.$transaccion->ciudad);

        /// Apartir de aqui empezamos con la tabla de productos
        $pdf::SetFont('Helvetica','B',12);

        $pdf::Ln(8);

        $header = array("DETALLE","MONTO"); //Definir cabecera
        $ancho_columna = array(153, 40); //Definir ancho de cabecera y columnas

        for($i=0;$i<count($header);$i++)
            $pdf::Cell($ancho_columna[$i],7,$header[$i],1,0,'C');
        $pdf::Ln();

        if($transaccion->tipo_transaccion_id=='C' && $transaccion->tipo_cuenta_id=='G') { //Si es CREDITO y GIRO
            $producto = array(
                //array($transaccion->detalle,$transaccion->monto),
                array("Monto a ser enviado",$transaccion->monto),
                array("Cargos ",$transaccion->comision)
            );
        } else {
            $producto = array(
                array($transaccion->detalle,$transaccion->monto)
            );
        }

        //Generar datos del cuerpo del comprobante
        $pdf::SetFont('Helvetica','',12);
        $total = 0;
        foreach($producto as $row)
        {
            $pdf::Cell($ancho_columna[0],6,$row[0],'1',0,'C');
            $pdf::Cell($ancho_columna[1],6,'USD '.number_format($row[1],2,".",","),'1',0,'R');
            $pdf::Ln();//salto de linea
            $total+=$row[1];
        }

        //Generar numero a literal
        $pdf::Ln();

        $pdf::SetFont('Helvetica','',11);
        $pdf::Cell($ancho_columna[0],6,'TOTAL : '.$this->numerosEnLetras->convertir($total,'Dólares Americanos',true,''),'0',0,'R');
        $pdf::SetFont('Helvetica','B',12);
        $pdf::Cell($ancho_columna[1],6,'USD '.number_format($total,2,".",","),'0',0,'R');


       //// Apartir de aqui esta la tabla con los subtotales y totales
        $yposdinamic = ($posY + 65) + (count($producto)*15);

        $pdf::SetFont('Helvetica','',12);
        $pdf::setXy(50,$yposdinamic + 20);
        $pdf::Cell(5,6,$transaccion->cliente_ordenante,'0',0,'C');
        $pdf::SetFont('Helvetica','',10);
        $pdf::setXy(50,$yposdinamic + 25);
        $pdf::Cell(5,6,'Doc. '.$transaccion->nro_documento,'0',0,'C');


        $pdf::SetFont('Helvetica','',12);
        $pdf::setXy(110,$yposdinamic + 20);
        $pdf::Cell(5,6,'Firma y sello de caja');

        //Generar codigo QR
        $textQR=$transaccion->cliente_ordenante.'|'.$transaccion->cliente_cuenta.'|'.$transaccion->fecha.'|USD:'.$total;
        $this->QR_Code($transaccion->transaccion_id,$textQR);
        $pdf::ImageSVG('qrcodes/'.$transaccion->transaccion_id.'.svg',168,92,35,35);
        //$pdf->ImageSVG(ARCHIVO,X,X,ANCHO,ALTO, $link='', $align='', $palign='', $border=0, $fitonpage=false);

        $pdf::Output(public_path('pdf/comprobante/'.$transaccion->transaccion_id.'.pdf'), 'F');
    }

    //Generar codigo QR
    public function QR_Code($transaccion_id,$textoQR){
      QrCode::format('svg');
      QrCode::generate($textoQR,public_path('qrcodes/'.$transaccion_id.'.svg'));
    }

    //Generar comprobante PDF impresora termica
    public function comprobante_impresora_ternica($transaccion){ //Esta seccion aun no esta en uso, pero funciona

        $agencia=$this->agenciaRep->obtenerAgenciaPorId($transaccion->agencia_id);

        $pdf = new Fpdf($orientation='P',$unit='mm', array(80,150));
        $pdf::AddPage();

        // CABECERA

        $pdf::SetFont('Helvetica','B',12);
        $pdf::Cell(60,4,'Nro. '.$transaccion->transaccion_id,0,1,'C');
        $pdf::SetFont('Helvetica','',8);
        $pdf::Cell(60,4,$agencia->direccion,0,1,'C');
        $pdf::SetFont('Helvetica','',7);
        $pdf::Cell(60,4,$agencia->fono,0,1,'C');
        $pdf::Cell(60,4,$agencia->observacion,0,1,'C');


        // DATOS FACTURA
        $pdf::Ln(3);
        $pdf::Cell(60,4,'Fecha    : '.$transaccion->fecha,0,1,'');
        $pdf::Cell(60,4,'Ordeante : '.$transaccion->cliente_ordenante,0,1,'');
        $pdf::Cell(60,4,'Cuenta   : '.$transaccion->cliente_cuenta,0,1,'');
        $pdf::Cell(60,4,'Destino  : '.$transaccion->pais.' '.$transaccion->ciudad,0,1,'');

        //CABECERA
        $pdf::SetFont('Helvetica', 'B', 7);
        $pdf::Cell(45, 10, 'Detalle',0,0,'C');
        $pdf::Cell(15, 10, 'Total',0,0,'R');
        $pdf::Ln(8);
        $pdf::Cell(60,0,'','T');
        $pdf::Ln(1);

        // PRODUCTOS
         $pdf::SetFont('Helvetica', '', 7);
         $pdf::MultiCell(45,4,$transaccion->detalle,0,'L');
         $pdf::Cell(60, -5,'$'.number_format($transaccion->monto,2,".",","),0,0,'R');


        // TOTAL LITERAL
        $pdf::Ln(6);
        $pdf::Cell(60,0,'','T');
        $pdf::Ln(2);
        $pdf::MultiCell(45, 4,'TOTAL : '.$this->numerosEnLetras->convertir($transaccion->monto,'Dolares',true),0,'L');
        $pdf::Cell(60, -8,'$'.number_format($transaccion->monto,2,".",","),0,0,'R');


        // PIE DE PAGINA
        $pdf::Ln(12);
        $pdf::Cell(60,0,$transaccion->cliente_ordenante,0,1,'C');
        $pdf::Ln(3);
        $pdf::Cell(60,0,'Doc. '.$transaccion->nro_documento,0,1,'C');

        $pdf::Ln(20);
        $pdf::Cell(60,0,'Firma y sello de caja',0,1,'C');


        $pdf::output('F','pdf/comprobante/'.$transaccion->transaccion_id.'.pdf');
    }

}//fin clase
