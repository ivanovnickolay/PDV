<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.08.2016
 * Time: 23:05
 */

namespace App\Utilits\loadDataExcel\createReaderFile {

    use org\bovigo\{
        vfs\vfsStream, vfs\vfsStreamDirectory
    };
    use PhpOffice\{
        PhpSpreadsheet\Reader\Csv, PhpSpreadsheet\Reader\Xls, PhpSpreadsheet\Reader\Xlsx
    };
    use PHPUnit\Framework\{
        TestCase
    };

    class getReaderExcelTest extends TestCase
    {
        /**
         * @var vfsStreamDirectory
         */
        private $file_system;

        /**
         * формируем виртуальную файловую систему
         */
        public function setUp()
        {
            // define my virtual file system
            /** @var array $directory */
            $directory=[
                'testFile'=> array(
                    'test.xls',
                    'test1.xlsx',
                    'test2.csv'=>"текст,12.11,12.08.2016\nsecond1,second2,second3\nthird1,third2,third3"
                )
            ];
            // setup and cache the virtual file system
            $this->file_system = vfsStream::setup('root', 444, $directory);
        }

        /**
         * Проверка создания Ридера нужного типа по расширению xls
         * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
         */
        public function testCreateReaderXLS()
        {
            $reader=new getReaderExcel($this->file_system->url().'test.xls');
            $reader->createFilter('F');
            $obj=$reader->getReader();
            $this->assertInstanceOf(Xls::class,$obj);
        }

        /**
         * Проверка создания Ридера нужного типа по расширению xlsx
         * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
         */
        public function testCreateReaderXLSX()
        {
            $reader=new getReaderExcel($this->file_system->url().'test1.xlsx');
            $reader->createFilter('F');
            $obj=$reader->getReader();
            $this->assertInstanceOf(Xlsx::class,$obj);
        }


        /**
         * Проверка создания Ридера нужного типа по расширению csv
         * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
         */
        public function testCreateReaderCSV()
        {
            $reader=new getReaderExcel($this->file_system->url().'test2.csv');
            $reader->createFilter('F');
            $obj=$reader->getReader();
            $this->assertInstanceOf(Csv::class,$obj);
        }


        /**
         * Тестируем чтение значений из тестового файла (он должен быть в папке с тестом !!))
         *  - проверяем следующие значения
         *      - текст
         *      - дата (важно установить часовой пояс Europe/Kiev
     *          - цифровое значение
         * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
         * @throws \Exception
         * @throws \PhpOffice\PhpSpreadsheet\Exception
         */
        public function test_getRowDataArray()
        {
            $fileName= __DIR__.'\\test.xlsx';
            $reader= new getReaderExcel($fileName);
            //$reader=new getReaderExcel("C:\\OSPanel\\domains\\PDV_UZ\\tests\\Utilits\\loadDataExcel\\createReaderFile\\test.xlsx");
            $reader->createFilter('F');
                // создаем класс Ридера PHPExcel_Reader_Excel2007
                $reader->getReader();
                 // ПОЛУЧАЕМЫЙ МАССИВ ДВУХ МЕРНЫЙ !!!!
                    // получаем загруженный файд согластно установленным фильтрам
                    $reader->loadDataFromFileWithFilter(2);
                    $arr=$reader->getRowDataArray(2);
                        // получение первого столбца
                        $this->assertEquals("текст",$arr[0][0]);
                        // получение столбца даты
            try {
                $this->assertEquals(
                    new \DateTime("12.08.2016"),
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($arr[0][2], 'Europe/Kiev')
                );
            } catch (\Exception $e) {
            }
            // Знак разделения дробных частей - точка (((((
                        $this->assertEquals("19.11",$arr[0][1]);
        }

        /**
         * Тестируем возможность получения количества заполненных строк в таблице
         * @throws \App\Utilits\loadDataExcel\Exception\errorLoadDataException
         */
        public function test_getMaxRow()
        {

            $fileName= __DIR__.'\\test.xlsx';
            $reader= new getReaderExcel($fileName);
            //$reader=new getReaderExcel("C:\\OSPanel\\domains\\PDV_UZ\\tests\\Utilits\\loadDataExcel\\createReaderFile\\test.xlsx");
            $reader->createFilter('F');
            $reader->getReader();
            $this->assertEquals(30,$reader->getMaxRow());
        }
    }
}
