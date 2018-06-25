<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 03.08.2016
 * Time: 23:33
 *  https://habrahabr.ru/post/148203/
 * Собственно класс chunkReadFilter — это то, что нам нужно.
 * Устанавливаем его в качестве фильтра для чтения файла, и файл будет загружаться не целиком,
 * а лишь определенное количество строк.

 */

namespace App\Utilits\loadDataExcel\createReaderFile;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;


/**
 * Реализация фильтра для чтения информации из файла.
 * Уменьшает использование ресурсов из-за частичного чтения иформации,
 * что экономит оперативную память
 * Class chunkReadFilter
 * @package LoadDataExcelBundle\Util\chunkReadFilter
 */

class chunkReadFilter implements IReadFilter
{
	/**
	 * @var int
	 */
	private $_startRow = 0;
	/**
	 * @var int
	 */
	private $_endRow = 0;
	/**
	 * @var array
	 */
	private $_column=array();

	/**
     * получаем последние буквы столбца данные их которого будем считывать
     * первая строка всегда "А"
     * chunkReadFilter constructor.
     * @param string $columnLast последний столбей диапазона столбцов которые будут считыватся
     */
    public function __construct (string $columnLast)
    {
        $this->_column=$this->createColumnsArray($columnLast);
    }

	/**
	 * Установка строк для чтения
	 * @param $startRow int первая строка для чтения данных
	 * @param $chunkSize int количества строк которые считываются с файла за один раз
	 */
	public function setRows($startRow, $chunkSize) {
        $this->_startRow    = $startRow;
        $this->_endRow      = $startRow + $chunkSize;
    }

	/**
	 * Чтение информации с учетом ограничений
	 * @param String $column
	 * @param int $row
	 * @param string $worksheetName
	 * @return bool
	 */
	public function readCell($column, $row, $worksheetName = '') {
        if (($row >= $this->_startRow && $row < $this->_endRow)) {
            if (in_array($column,$this->_column)) {
                return true;
            }
        }
        return false;
    }
    /**
     * Создаем массив столбцов для фильтра
     * @link http://stackoverflow.com/questions/14278603/php-range-from-a-to-zz
     * @link http://php.net/manual/ru/function.range.php#107440
     * @param $end_column
     * @param string $first_letters
     * @return array
     */
    private function createColumnsArray($end_column, $first_letters = '')
    {
        $columns = array();
        $length = strlen($end_column);
        $letters = range('A', 'Z');

        // Iterate over 26 letters.
        foreach ($letters as $letter) {
            // Paste the $first_letters before the next.
            $column = $first_letters . $letter;

            // Add the column to the final array.
            $columns[] = $column;

            // If it was the end column that was added, return the columns.
            if ($column == $end_column)
                return $columns;
        }

        // Add the column children.
        foreach ($columns as $column) {
            // Don't itterate if the $end_column was already set in a previous itteration.
            // Stop iterating if you've reached the maximum character length.
            if (!in_array($end_column, $columns) && strlen($column) < $length) {
                $new_columns =$this->createColumnsArray($end_column, $column);
                // Merge the new columns which were created with the final columns array.
                $columns = array_merge($columns, $new_columns);
            }
        }

        return $columns;
    }
}