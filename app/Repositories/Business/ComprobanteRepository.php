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
    public function comprobante_reserva($reserva,$detalle){

        //https://tcpdf.org Manual tcpdf

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
         $pdf::Cell(20,6,$reserva->id,0,1,'L');

        //Datos de fecha
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+50);
        $pdf::Cell(5,6,"Fecha :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(35,$posY+50);
        $pdf::Cell(5,6,$reserva->fecha);

        //Datos del cliente
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+55);
        $pdf::Cell(5,6,"Cliente :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(35,$posY+55);
        $pdf::Cell(5,6,$reserva->cliente);

        //Datos del cliente
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+55);
        $pdf::Cell(5,6,"Servicio :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(35,$posY+60);
        $pdf::Cell(5,6,$reserva->servicio);

         //Titulo del comprobante
         $pdf::ln(10);
         $pdf::SetFont('Helvetica','B',16);
         $pdf::Cell(0,4,"DETALLE DE RESERVA",0,1,'C');

        /// Apartir de aqui empezamos con la tabla de productos
        $pdf::ln(15);

        $tbody="";
        $total=0;
        foreach($detalle as $row)
        {
            $tbody=$tbody.'<tr>
                <td align="center" width="8%">1</td>
                <td align="left" width="37%">'.$row->fecha_ini.'</td>
                <td align="left" width="18%">'.$row->fecha_fin.'</td>
                <td align="center" width="12%">'.number_format($row->monto,2,".",",").'</td>
            </tr>';

            $total=$total+$row->monto;
        }

        $html= '<table border="1" cellspacing="0" style="text-align:center;">

                    <thead><tr bgcolor="#58D68D">
                        <th width="8%"><strong>Cant</strong></th>
                        <th width="15%"><strong>Fecha Ingreso</strong></th>
                        <th width="37%"><strong>Fecha Salida</strong></th>
                        <th width="12%"><strong>Monto</strong></th>
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
        $pdf::Output(public_path('pdf/comprobante/reserva/'.$compra->compra_id.'.pdf'), 'F');
    }

}//fin clase
