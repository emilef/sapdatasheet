<?php

$__ROOT__ = dirname(dirname(dirname(__FILE__)));
require_once ($__ROOT__ . '/include/3rdparty/php-export-data/php-export-data.class.php');
include_once ($__ROOT__ . "/include/3rdparty/php_xlsxwriter/xlsxwriter.class.php");

/**
 * Download file Format
 */
class DOWNLOAD {

    const FORMAT_CSV = "CSV";
    const FORMAT_CSV_Title = "CSV file";
    const FORMAT_XLS = "XLS";
    const FORMAT_XLS_Title = "Excel 97-2003 Worksheet (.xls) file";
    const FORMAT_XLSX = "XLSX";
    const FORMAT_XLSX_Title = "Excel Open XML Format Spreadsheet (.xlsx) file";

    /**
     * Download the file in CSV format
     */
    public static function AsCSV($result, $filename) {

        // send response headers to the browser
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=' . $filename . '.' . strtolower(DOWNLOAD::FORMAT_CSV));
        $fp = fopen('php://output', 'w');

        // output header row (if at least one row exists)
        fputcsv($fp, array_keys($result[0]));

        // output each row
        foreach ($result as $row) {
            fputcsv($fp, $row);
        }

        fclose($fp);
    }

    /**
     * Download the file in Excel 2003 XML format (.xls).
     */
    public static function AsXLS($result, $filename) {
        $exporter = new ExportDataExcel('browser', $filename . '.' . strtolower(DOWNLOAD::FORMAT_XLS));
        $exporter->title = $filename;
        $exporter->initialize();                  // starts streaming data to web browser

        // Table Header
        $exporter->addRow(array_keys($result[0]));

        // Table Rows
        foreach ($result as $row) {
            $exporter->addRow(array_values($row));
        }

        $exporter->finalize();                    // writes the footer, flushes remaining data to browser.
        exit();                                   // all done
    }

    /**
     * Download the file in Excel 2007+ format (.xlsx).
     */
    public static function AsXLSX($result, $filename) {
        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename . '.' . strtolower(DOWNLOAD::FORMAT_XLSX)) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        $rows = array();
        array_push($rows, array_keys($result[0]));
        foreach ($result as $row) {
            array_push($rows, array_values($row));
        }

        $writer = new XLSXWriter();
        $writer->setAuthor('www.sapdatasheet.org');
        $writer->writeSheet($rows, $filename);
        $writer->writeToStdOut();
        exit(0);
    }

}
