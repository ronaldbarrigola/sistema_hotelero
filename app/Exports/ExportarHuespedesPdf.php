<?php
namespace App\Exports;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Business\NumerosEnLetras;
use App\Repositories\Base\AgenciaRepository;
use Carbon\Carbon;

class ExportarHuespedesPdf
{
    protected $agenciaRep;
    protected $numerosEnLetras;

    public function __construct(AgenciaRepository $agenciaRep,NumerosEnLetras $numerosEnLetras){
        $this->agenciaRep=$agenciaRep;
        $this->numerosEnLetras=$numerosEnLetras;
    }

    function exportar($huespedes,$fecha_ini,$fecha_fin){
        Carbon::setLocale(config('app.locale')); //config/app.php se configura 'locale' => 'es'
        $titulo_fecha="";
        if(strcmp ($fecha_ini,$fecha_fin) == 0) {
           //$fecha_ini=($fecha_ini!=null)?Carbon::parse($fecha_ini)->format('d \d\e F \d\e Y'):"inicio";
           $fecha_ini=($fecha_ini!=null)?Carbon::parse($fecha_ini)->format('d/m/Y'):"inicio";
           $titulo_fecha=$fecha_ini;
        } else {
           $fecha_ini=($fecha_ini!=null)?Carbon::parse($fecha_ini)->format('d/m/Y'):"inicio";
           $fecha_fin=($fecha_fin!=null)?Carbon::parse($fecha_fin)->format('d/m/Y'):"final";
           $titulo_fecha=$fecha_ini.'  al '.$fecha_fin;
        }

        $agencia_id=Auth::user()->agencia_id;
        $agencia=$this->agenciaRep->obtenerAgenciaPorId($agencia_id);

        $pdf = new TCPDF();

        //Establecer numeros de pagina, se repiten en todas la paginas
        $pdf::setHeaderCallback(function ($pdf){
            //Numero de pagina
            $pdf->setY(3);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->Cell(0, 2, 'Página '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        });

        $pdf::AddPage('P','cm','Oficio', true, 'UTF-8', false);   //p: vertical  L:horizontal

        //Imagen Logo
        $pdf::Image('imagenes/logo_empresa.png',10,5,50,25,'PNG'); //$pdf::Image('imagen',X,Y,Ancho,Alto,'PNG')

        //CABECERA
        $pdf::SetFont('Helvetica','B',12);
        $pdf::Cell(0,2,'MOVIMIENTO DE PASAJEROS',0,1,'C');
        $pdf::SetFont('Helvetica','',10);
        $pdf::Cell(0,2,'Direccion : '.$agencia->direccion,0,1,'C');
        $pdf::Cell(0,2,'Telefono : '.$agencia->fono,0,1,'C');
        //$pdf::SetFont('Helvetica','',8);
        $pdf::Cell(0,2,'Fecha : '.$titulo_fecha,0,1,'C');

        //GENERAR DATA
        $pdf::ln(3);
        $pdf::SetFont('Helvetica','B',10);
        $pdf::Cell(0,2,'INGRESO',0,1,'C');
        $pdf::SetFont('Helvetica','',8);
        //header
        $header='<thead><tr  bgcolor="#A4F2D5">
                    <th><strong>Fecha</strong></th>
                    <th><strong>Huesped</strong></th>
                    <th><strong>Num. Hab.</strong></th>
                    <th><strong>Pais</strong></th>
                    <th><strong>Ciudad</strong></th>
                    <th><strong>Profesion</strong></th>
                    <th><strong>Edad</strong></th>
                    <th><strong>Nro. Doc</strong></th>
                </tr></thead>';

        //Datos de INGRESO de huespedes
        $tbody="<tbody>";
        $huespedes_ingreso=$huespedes->where('movimiento','=','INGRESO');

        foreach($huespedes_ingreso as $row)
        {
            $tbody=$tbody.'<tr>
                <td align="center">'.$row->fecha_ingreso.'</td>
                <td align="left">'.$row->huesped.'</td>
                <td align="center">'.$row->num_habitacion.'</td>
                <td align="left">'.$row->pais.'</td>
                <td align="left">'.$row->ciudad.'</td>
                <td align="left">'.$row->profesion.'</td>
                <td align="center">'.$row->edad.'</td>
                <td align="left">'.$row->doc_id.'</td>
             </tr>';
        }
       $tbody=$tbody.'</tbody>';
       $html= '<table border="1" cellspacing="0" style="text-align:center;">'.$header.$tbody.'</table>';
       $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

       //Datos de PERMANENCIA de huespedes
       $pdf::ln(2);
       $pdf::SetFont('Helvetica','B',10);
       $pdf::Cell(0,2,'PERMANENCIA',0,1,'C');
       $pdf::SetFont('Helvetica','',8);
       $tbody="<tbody>";
       $huespedes_permanencia=$huespedes->where('movimiento','=',"PERMANENCIA");

       foreach($huespedes_permanencia as $row)
       {
           $tbody=$tbody.'<tr>
               <td align="center">'.$row->fecha_ingreso.'</td>
               <td align="left">'.$row->huesped.'</td>
               <td align="center">'.$row->num_habitacion.'</td>
               <td align="left">'.$row->pais.'</td>
               <td align="left">'.$row->ciudad.'</td>
               <td align="left">'.$row->profesion.'</td>
               <td align="center">'.$row->edad.'</td>
               <td align="left">'.$row->doc_id.'</td>
            </tr>';
       }
      $tbody=$tbody.'</tbody>';
      $html= '<table border="1" cellspacing="0" style="text-align:center;">'.$header.$tbody.'</table>';
      $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

      //Datos de SALIDA de huespedes
      $pdf::ln(2);
      $pdf::SetFont('Helvetica','B',10);
      $pdf::Cell(0,2,'SALIDA',0,1,'C');
      $pdf::SetFont('Helvetica','',8);
      $tbody="<tbody>";
      $huespedes_salida=$huespedes->where('movimiento','=','SALIDA');

      foreach($huespedes_salida as $row)
      {
          $tbody=$tbody.'<tr>
              <td align="center">'.$row->fecha_salida.'</td>
              <td align="left">'.$row->huesped.'</td>
              <td align="center">'.$row->num_habitacion.'</td>
              <td align="left">'.$row->pais.'</td>
              <td align="left">'.$row->ciudad.'</td>
              <td align="left">'.$row->profesion.'</td>
              <td align="center">'.$row->edad.'</td>
              <td align="left">'.$row->doc_id.'</td>
           </tr>';
      }
     $tbody=$tbody.'</tbody>';
     $html= '<table border="1" cellspacing="0" style="text-align:center;">'.$header.$tbody.'</table>';
     $pdf::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

     $pdf::Output('Huesped.pdf', 'D');

      //I: envía el fichero al navegador de forma que se usa la extensión (plug in) si está disponible.
      //D: envía el fichero al navegador y fuerza la descarga del fichero con el nombre especificado por name.
      //F: guarda el fichero en un fichero local de nombre name.
      //S: devuelve el documento como una cadena.

      exit; //Para solucionar descarga desde el lado server
    }

}
