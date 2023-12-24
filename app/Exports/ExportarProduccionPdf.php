<?php
namespace App\Exports;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Repositories\Business\NumerosEnLetras;

use Carbon\Carbon;

class ExportarProduccionPdf
{
    protected $numerosEnLetras;

    public function __construct(NumerosEnLetras $numerosEnLetras){
        $this->numerosEnLetras=$numerosEnLetras;
    }

    function exportar($produccion,$fecha_ini,$fecha_fin){

        $fecha_ini=($fecha_ini!=null)?$fecha_ini:"";
        $fecha_fin=($fecha_fin!=null)?$fecha_fin:"";

        $pdf = new TCPDF();

        //Establecer margenes de pagina
        //$pdf::SetMargins(10, 12, 10, true);  //(left,top,right,$keepmargins = false)

        //Establecer numeros de pagina, se repiten en todas la paginas
        $pdf::setHeaderCallback(function ($pdf){
            //Numero de pagina
            $pdf->setY(3);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->Cell(0, 2, 'Página '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

        });

        $pdf::AddPage('L','cm','Letter', true, 'UTF-8', false);  // AddPage [P (vertical), L (horizontal)], el formato (A4-A5-ETC)

        //Imagen Logo
        $pdf::Image('imagenes/logo_empresa.png',10,5,80,30,'PNG');

        //CABECERA
        $pdf::SetFont('Helvetica','B',14);
        $pdf::Cell(0,2,'REPORTE DE PRODUCCION',0,1,'C');
        $pdf::SetFont('Helvetica','',12);
        $pdf::Cell(0,2,'Expresado en Bolivianos',0,1,'C');
        $pdf::Cell(0,2,'Desde el : '.$fecha_ini.' Hasta el :'.$fecha_fin,0,1,'C');

        $pdf::ln();
        $pdf::SetFont('Helvetica','',12);
        $pdf::Cell(0,3,'Fecha de emision : '.Carbon::now()->format('d/m/Y'),0,1,'R');

        //GENERAR DATA
        $pdf::ln(3);
        $pdf::SetFont('Helvetica','',8);
        //header
        $header='<thead><tr  bgcolor="#CCE5FF">
                    <th><strong>Nro. Reserva</strong></th>
                    <th><strong>Fecha</strong></th>
                    <th><strong>Num. Hab.</strong></th>
                    <th><strong>Producto</strong></th>
                    <th><strong>Cliente</strong></th>
                    <th><strong>Tipo Transaccion</strong></th>
                    <th><strong>Ingreso</strong></th>
                </tr></thead>';
        //body
        $tbody="<tbody>";
        $total=0;
        foreach($produccion as $row)
        {
            $tbody=$tbody.'<tr>
                <td align="center">'.$row->reserva_id.'</td>
                <td align="left">'.$row->fecha.'</td>
                <td align="center">'.$row->num_habitacion.'</td>
                <td align="left">'.$row->producto.'</td>
                <td align="left">'.$row->cliente.'</td>
                <td align="left">'.$row->tipo_transaccion.'</td>
                <td align="center">'.$row->monto.'</td>
             </tr>';
             $total=$total + $row->monto;
        }
       $tbody=$tbody.'</tbody>';

       $footer= '<tfoot>
                <tr>
                    <td colspan="6" style="text-align:right;"><strong>TOTAL INGRESO</strong></td>
                    <td>'.$total.'</td>
                </tr>
                </tfoot>';

       $html= '<table border="1" cellspacing="0" style="text-align:center;">'.$header.$tbody.$footer.'</table>';
       $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
       $pdf::Output('Produccion.pdf', 'D');

       //I: envía el fichero al navegador de forma que se usa la extensión (plug in) si está disponible.
       //D: envía el fichero al navegador y fuerza la descarga del fichero con el nombre especificado por name.
       //F: guarda el fichero en un fichero local de nombre name.
       //S: devuelve el documento como una cadena.

       exit; //Para solucionar descarga desde el lado server
    }

}
