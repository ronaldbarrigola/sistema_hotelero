<?php

namespace App\Repositories\Business;

use App\Repositories\Base\AgenciaRepository;
use App\Repositories\Business\NumerosEnLetras;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Auth;

class ComprobanteRepository{

    protected $agenciaRep;
    protected $numerosEnLetras;

    public function __construct(AgenciaRepository $agenciaRep,NumerosEnLetras $numerosEnLetras){
        $this->agenciaRep=$agenciaRep;
        $this->numerosEnLetras=$numerosEnLetras;
    }

    //Generar comprobante PDF papel carta
    public function comprobante_detalle_cargo($reserva,$detalle){

        //https://tcpdf.org Manual tcpdf

        $agencia_id=Auth::user()->agencia_id;
        $agencia=$this->agenciaRep->obtenerAgenciaPorId($agencia_id);

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
        $pdf::Image('imagenes/logo_empresa.png',10,5,50,25,'PNG'); //$pdf::Image('imagen',X,Y,Ancho,Alto,'PNG')

        // Agregamos los datos de la empresa
        $posY=0;
        $pdf::SetFont('Helvetica','B',15);
        $pdf::SetXY(80,$posY+10);
        $pdf::Cell(60,6,"HOTEL WENDYMAR",0,1,'C');
        $pdf::SetFont('Helvetica','',10);
        $pdf::SetXY(80,$posY+15);
        $pdf::Cell(60,6,"Direccion :".$agencia->direccion,0,1,'C');
        $pdf::SetXY(80,$posY+20);
        $pdf::Cell(60,6,"Telefono :".$agencia->fono,0,1,'C');
        $pdf::SetXY(80,$posY+25);
        $pdf::Cell(60,6,"COPACABANA - BOLIVIA",0,1,'C');

        //Numero de reserva
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(170,$posY+15);
        $pdf::Cell(4,6,"Nro. ");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(180,$posY+15);
        $pdf::Cell(20,6,$reserva->id,0,1,'L');

        //Datos de fecha
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+35);
        $pdf::Cell(5,6,"Fecha :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(35,$posY+35);
        $pdf::Cell(5,6,$reserva->fecha);

        //Datos del cliente
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+40);
        $pdf::Cell(5,6,"Cliente :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(35,$posY+40);
        $pdf::Cell(5,6,$reserva->cliente);

        //Datos del servicio
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+45);
        $pdf::Cell(5,6,"Servicio :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(35,$posY+45);
        $pdf::Cell(5,6,$reserva->servicio);

        //Datos Nro de habitacion
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+50);
        $pdf::Cell(5,6,"Nro. Hab. :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(35,$posY+50);
        $pdf::Cell(5,6,$reserva->num_habitacion);

        //Datos de habitacion
        $pdf::SetFont('Helvetica','B',12);
        $pdf::SetXY(9,$posY+55);
        $pdf::Cell(5,6,"Tipo Hab. :");
        $pdf::SetFont('Helvetica','',12);
        $pdf::SetXY(35,$posY+55);
        $pdf::Cell(5,6,$reserva->tipo_habitacion);

        //Titulo del comprobante
        $pdf::ln(6);
        $pdf::SetFont('Helvetica','B',14);
        $pdf::Cell(0,4,"DETALLE DE CARGOS",0,1,'C');

        /// Apartir de aqui empezamos con la tabla de productos
        $pdf::ln(2);
        $pdf::SetFont('Helvetica','',9);
        $tbody="";
        $total=0;
        $descuento_total=0;
        $cargo_total=0;
        $pago_total=0;
        $saldo_total=0;
        foreach($detalle as $row)
        {
            $tbody=$tbody.'<tr>
                <td align="center">'.$row->num_habitacion.'</td>
                <td align="left">'.$row->fecha.'</td>
                <td align="left">'.$row->producto.'</td>
                <td align="center">'.$row->cantidad.'</td>
                <td align="right">'.$row->precio_unidad.'</td>
                <td align="right">'.number_format($row->cantidad*$row->precio_unidad,2,".",",").'</td>
                <td align="right">'.number_format($row->descuento,2,".",",").'</td>
                <td align="right">'.number_format($row->cargo,2,".",",").'</td>
                <td align="right">'.number_format($row->pago,2,".",",").'</td>
                <td align="right">'.number_format($row->saldo,2,".",",").'</td>
            </tr>';
            $total=$total + $row->cantidad*$row->precio_unidad;
            $descuento_total=$descuento_total + $row->descuento;
            $cargo_total=$cargo_total + $row->cargo;
            $pago_total=$pago_total + $row->pago;
            $saldo_total=$saldo_total + $row->saldo;
        }

        $html= '<table border="1" cellspacing="0" style="text-align:center;">
                    <thead><tr bgcolor="#58D68D">
                        <th><strong>Hab.</strong></th>
                        <th><strong>Fecha</strong></th>
                        <th><strong>Detalle</strong></th>
                        <th><strong>Cant</strong></th>
                        <th><strong>P/U</strong></th>
                        <th><strong>Total</strong></th>
                        <th><strong>Dscto</strong></th>
                        <th><strong>Cargo</strong></th>
                        <th><strong>Pago</strong></th>
                        <th><strong>Saldo</strong></th>
                    </tr></thead>
                <tbody>'.$tbody.'</tbody>
                <tfoot>
                   <tr>
                      <th colspan="5">TOTAL</th>
                      <th align="right">'.number_format($total,2,".",",").'</th>
                      <th align="right">'.number_format($descuento_total,2,".",",").'</th>
                      <th align="right">'.number_format($cargo_total,2,".",",").'</th>
                      <th align="right">'.number_format($pago_total,2,".",",").'</th>
                      <th align="right">'.number_format($saldo_total,2,".",",").'</th>
                   </tr>
                </tfoot>
                </table>';

        $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf::ln(5);
        $pdf::SetFont('Helvetica','',12);
        $html_footer='<table border="0">
            <hr>
            <tr>
                <td colspan="4" style="text-align:right;">TOTAL A PAGAR :'.$this->numerosEnLetras->convertir($saldo_total,'Bolivianos',true).'</td>
                <td style="text-align:center;"><strong>'.number_format($saldo_total,2,".",",").'</strong></td>
            </tr>
        </table>';
        $pdf::writeHTMLCell(0, 0, '', '', $html_footer, 0, 1, 0, true, '', true);

        $pdf::Output(public_path('pdf/comprobante/detalle_cargo/'.$reserva->id.'.pdf'), 'F');
    }

}//fin clase
