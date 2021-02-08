<?php
declare (strict_types=1);

use Slam\Excel\Helper as ExcelHelper;

class ExportData
{
    public function exportarExcel(array $array, $filename, $title)
    {
        $arrayData = new ArrayIterator($array);
        $columnCollection = new ExcelHelper\ColumnCollection([
            new ExcelHelper\Column('', '', 15, new ExcelHelper\CellStyle\Text())
        ]);
        $filename = sprintf('Excel/' . $filename, __DIR__, uniqid());
        $phpExcel = new ExcelHelper\TableWorkbook($filename);
        $worksheet = $phpExcel->addWorksheet($title);

        $table = new ExcelHelper\Table($worksheet, 0, 0, 'Data ' . $title, $arrayData);
        $table->setColumnCollection($columnCollection);

        $phpExcel->writeTable($table);
        $phpExcel->close();
    }
}


?>