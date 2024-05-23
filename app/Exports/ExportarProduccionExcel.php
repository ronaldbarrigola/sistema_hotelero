<?php
namespace App\Exports;

use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ExportarProduccionExcel
{
    const FIL_INI=2;
    const COL_INI=1;
    public function exportar($produccion){
        try {
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
            $activeSheet->setTitle('PRODUCCION');
            $activeSheet->getParent()->getDefaultStyle()->getFont()->setName('Arial');
            $activeSheet->setCellValue('A1', 'REPORTE DE PRODUCCION');
            $activeSheet->getStyle("A1")->getFont()->setSize(16);
            $activeSheet->mergeCells('A1:H1');

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
            $activeSheet->setCellValue('B'.self::FIL_INI, 'Fecha ');
            $activeSheet->setCellValue('C'.self::FIL_INI, 'Num. Hab.');
            $activeSheet->setCellValue('D'.self::FIL_INI, 'Producto');
            $activeSheet->setCellValue('E'.self::FIL_INI, 'Cliente');
            $activeSheet->setCellValue('F'.self::FIL_INI, 'Canal Reserva');
            $activeSheet->setCellValue('G'.self::FIL_INI, 'Tipo Transaccion');
            $activeSheet->setCellValue('H'.self::FIL_INI, 'Monto');

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

            $activeSheet->getStyle("A".self::FIL_INI.":H".self::FIL_INI)->applyFromArray($styleArray);

            // LLENANDO FILAS
            $i=self::FIL_INI;
            foreach($produccion as $row) {
                $i = $i+1;
                $activeSheet->setCellValue('A'.$i , $row->reserva_id);
                $activeSheet->setCellValue('B'.$i , $row->fecha);
                $activeSheet->setCellValue('C'.$i , $row->num_habitacion);
                $activeSheet->setCellValue('D'.$i , $row->producto);
                $activeSheet->setCellValue('E'.$i , $row->cliente);
                $activeSheet->setCellValue('F'.$i , $row->canal_reserva);
                $activeSheet->setCellValue('G'.$i , $row->tipo_transaccion);
                $activeSheet->setCellValue('H'.$i , $row->monto);

                $activeSheet->getStyle("A".$i.":H".$i)->applyFromArray($styleArray2); //Establecer bordes
            }

            foreach (range('A','H') as $col) {
                $activeSheet->getColumnDimension($col)->setAutoSize(true);
            }

            //Redirect output to a client's web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Produccion.xlsx"'); //Nombre a archivo
            header('Cache-Control: max-age=0');
            //If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            //If you're serving to IE over SSL, then the following may be needed
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







