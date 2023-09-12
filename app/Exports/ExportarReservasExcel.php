<?php
namespace App\Exports;

use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ExportarReservasExcel
{
    const FIL_INI=2;
    const COL_INI=1;
    public function exportar($compras){
        try {
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
            $activeSheet->setTitle('RESERVA');
            $activeSheet->getParent()->getDefaultStyle()->getFont()->setName('Arial');
            $activeSheet->setCellValue('A1', 'REPORTE DE RESERVAS');
            $activeSheet->getStyle("A1")->getFont()->setSize(16);
            $activeSheet->mergeCells('A1:N1');


            //CONFIGURACION DE PAGINA PARA IMPRESION
            $activeSheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $activeSheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LETTER);
            $activeSheet->getPageSetup()->setFitToWidth(true); //ajustando el ancho a una pagina.
            // en pulgadas  ... conversion cm/pulg  cm/2.54
            $activeSheet->getPageMargins()
                ->setTop(1/2.54)
                ->setLeft(1/2.54)
                ->setRight(1/2.54)
                ->setBottom(1/2.54)
                ->setHeader(0/2.54)
                ->setFooter(0/2.54);

            //TITULO DE COLUMNAS PARA FILAS
            $activeSheet->setCellValue('A'.self::FIL_INI, 'Nro. Reserva');
            $activeSheet->setCellValue('B'.self::FIL_INI, 'Fecha Registro');
            $activeSheet->setCellValue('C'.self::FIL_INI, 'Cliente');
            $activeSheet->setCellValue('D'.self::FIL_INI, 'Numero Habitacion');
            $activeSheet->setCellValue('E'.self::FIL_INI, 'Tipo Habitacion');
            $activeSheet->setCellValue('F'.self::FIL_INI, 'Servicio');
            $activeSheet->setCellValue('G'.self::FIL_INI, 'Fecha Ingreso');
            $activeSheet->setCellValue('H'.self::FIL_INI, 'Fecha Salida');
            $activeSheet->setCellValue('I'.self::FIL_INI, 'Pais Procedencia');
            $activeSheet->setCellValue('J'.self::FIL_INI, 'Ciudad Procedencia');
            $activeSheet->setCellValue('K'.self::FIL_INI, 'Huesped Check In');
            $activeSheet->setCellValue('L'.self::FIL_INI, 'Huesped Check Out');
            $activeSheet->setCellValue('M'.self::FIL_INI, 'Total Huespedes');
            $activeSheet->setCellValue('N'.self::FIL_INI, 'Estado Reserva');

            //COLOR DE TITULO COLUMNAS
            $styleArray = array(
                    'font'  => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000066'),
                        'size'  => 11,
                        'name'  => 'Arial'
                    ),
                    'borders' => array(
                        'outline' => array(
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => array('rgb' => '000000'),
                        ),
                    ),
                    'fill' => array(
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => array('rgb' => 'f0f5f5')
                    )
            );

            $styleArray2 = array(
                'font'  => array(
                    'bold'  => false,
                    'size'  => 11,
                    'name'  => 'Arial'
                ),
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => Border::BORDER_THIN
                    ),
                )
            );

            $activeSheet->getStyle("A".self::FIL_INI.":N".self::FIL_INI)->applyFromArray($styleArray);

            // LLENANDO FILAS
            $i=self::FIL_INI;

            foreach($compras as $row) {
                $i = $i+1;
                $activeSheet->setCellValue('A'.$i , $row->id);
                $activeSheet->setCellValue('B'.$i , $row->fecha);
                $activeSheet->setCellValue('C'.$i , $row->cliente);
                $activeSheet->setCellValue('D'.$i , $row->num_habitacion);
                $activeSheet->setCellValue('E'.$i , $row->tipo_habitacion);
                $activeSheet->setCellValue('F'.$i , $row->servicio);
                $activeSheet->setCellValue('G'.$i , $row->fecha_ini);
                $activeSheet->setCellValue('H'.$i , $row->fecha_fin);
                $activeSheet->setCellValue('I'.$i , $row->pais);
                $activeSheet->setCellValue('J'.$i , $row->ciudad);
                $activeSheet->setCellValue('K'.$i , $row->huesped_checkin);
                $activeSheet->setCellValue('L'.$i , $row->huesped_checkout);
                $activeSheet->setCellValue('M'.$i , $row->huesped_total);
                $activeSheet->setCellValue('N'.$i , $row->estado_reserva);

                $activeSheet->getStyle("A".$i.":N".$i)->applyFromArray($styleArray2); //Establecer bordes
            }

            foreach (range('A','N') as $col) {
                $activeSheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Redirect output to a client's web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reseras.xlsx"'); //Nombre a archivo
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit; //Para solucionar descarga desde el lado server

    } catch (Exception $ee) {
        echo 'Excepción capturada: ',  $ee->getMessage(), "\n";
    }
    }
}







