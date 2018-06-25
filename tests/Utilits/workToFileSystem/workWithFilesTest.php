<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04.09.2016
 * Time: 17:04
 */

namespace AnalizPdvBundle\Tests\Utilits\loadData;


use App\Utilits\workToFileSystem\workWithFiles;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\{
    TestCase
};

class workWithFilesTest extends TestCase
{

    /**
     * @var vfsStream
     */
    private $file_system;
    /**
     * формируем виртуальную файловую систему
     */
    public function setUp()
    {
        $this->file_system = vfsStream::setup('root');
        $testFile = vfsStream::newDirectory('testFile')->at($this->file_system);
        // Create Files
        vfsStream::newFile('19082016095050_40075815_J1201508_TAB1.xls')->at($testFile);
        vfsStream::newFile('19082016095050_40075815_J1201508_TAB1.xlsz')->at($testFile);
        vfsStream::newFile('19082016100630_40075815_J1201508_TAB1.xlsx')->at($testFile);
        vfsStream::newFile('19082016100630_40075815_J1201508_TAB1_.xlsx')->at($testFile);
        vfsStream::newFile('19082016100632_40075815_J1201508_TAB2.xlsx')->at($testFile);
        vfsStream::newFile('19082016095050_40075815_J1201508_TAB1.xlsz')->at($testFile);
        vfsStream::newFile('test.xlsx')->at($testFile);
        vfsStream::newFile('test.doc')->at($testFile);
    }

    /**
     * @throws \Exception
     */
    public function test_getArrayFilesFromDir_Exception(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Директория для чтения файлов не найдена!");
        $arrayFiles = workWithFiles::getArrayFilesFromDir($this->file_system->url().'/gg');
        $this->assertEmpty($arrayFiles);
    }

    /**
     * тест на контроль возврата из файлов в каталоге только тех файлов которые удовлетворяют условию
     * @throws \Exception
     */
    public function test_getArrayFilesFromDir(){
        $rootPath = vfsStream::url('root/testFile');
        $arrayFiles = workWithFiles::getArrayFilesFromDir($rootPath);
        //var_dump($arrayFiles);
        // количество записей в возвращенном массиве
        $this->assertEquals(count($arrayFiles),3);
        //должны быть в возвращенном массива
            $this->assertEquals(in_array('vfs://root/testFile\19082016095050_40075815_J1201508_TAB1.xls',$arrayFiles),1);
            $this->assertEquals(in_array('vfs://root/testFile\19082016100630_40075815_J1201508_TAB1.xlsx',$arrayFiles),1);
            $this->assertEquals(in_array('vfs://root/testFile\19082016100632_40075815_J1201508_TAB2.xlsx',$arrayFiles),1);
        // не должны быть в возвращенном массиве
            $this->assertNotEquals(in_array('vfs://root/testFile\19082016095050_40075815_J1201508_TAB1.xlsz',$arrayFiles),1);
            $this->assertNotEquals(in_array('vfs://root/testFile\19082016100630_40075815_J1201508_TAB1_.xlsx',$arrayFiles),1);
            $this->assertNotEquals(in_array('vfs://root/testFile\19082016095050_40075815_J1201508_TAB1.xlsz',$arrayFiles),1);
            $this->assertNotEquals(in_array('vfs://root/testFile\test.xlsx',$arrayFiles),1);
            $this->assertNotEquals(in_array('vfs://root/testFile\test.doc',$arrayFiles),1);
    }

    /**
     * тестирование переноса файлов из каталога в каталог
     */
    public function test_moveFile(){
        vfsStream::newDirectory('testFileMove')->at($this->file_system);
        $toPath = vfsStream::url('root/testFileMove');
        workWithFiles::moveFiles('vfs://root/testFile\test.doc',$toPath);
        $this->assertFileNotExists('vfs://root/testFile\test.doc');
        $this->assertFileExists('vfs://root/testFileMove\test.doc');
    }

    /*
     * Тестирование метода createFileErrorValidation на выбрасывание исключения
     * при не нахождении каталога для сохранения файла
     */
    public function test_createFileErrorValidation_Error(){
        $this->expectException(\Exception::class);
            $this->expectExceptionMessage("Директория для сохранения файла не найдена");

                    workWithFiles::createFileErrorValidation($this->file_system->url().'/gg',"465465",array());

    }

    /**
     * тестирование создания файла с именем в каталоге
     */
    public function test_createFileErrorValidation_SaveFile_isFileExists(){
        $arrayError = [
          "1"=>"First error"
        ];
            $fileName = "19082016095050_40075815_J1201508_TAB1";
                vfsStream::newDirectory('dirError')->at($this->file_system);
                    $dirFromError =vfsStream::url('root/dirError');
                    try {
                        workWithFiles::createFileErrorValidation($dirFromError, $fileName, $arrayError);
                    }catch (\Exception $e){

                    }
                $this->assertFileExists($dirFromError."/".$fileName.".log");
    }

    /**
     * тестирование содержимого созданного файла
     */
    public function test_createFileErrorValidation_SaveFile_EqualsContentFile(){

        $arrayError = [
            "1"=>"First error",
            "2"=>"Two error",
        ];
           $fileName = "19082016095050_40075815_J1201508_TAB1";
                vfsStream::newDirectory('dirError')->at($this->file_system);
                    $dirFromError =vfsStream::url('root/dirError');
                        try {
                            workWithFiles::createFileErrorValidation($dirFromError, $fileName, $arrayError);
                        }catch (\Exception $e){

                        }
                    $testStr="";
                // алгоритм формирования строк для проверки идентичный алгоритму в тестируемом методе
                foreach ($arrayError as $key=>$value){
                    $testStr = $testStr. "Строка № $key содержит ошибки =>> $value\n";
                }
           $content = file_get_contents($dirFromError."/".$fileName.".log");
        $this->assertEquals($testStr,$content);
        //echo $testStr;
        //echo $content;
    }

    public function datavalidFile()
    {
        return array(
            ["19082016095050_40075815_J1201508_TAB1.xls",true],
            ["19082016095050_40075815_J1201508_TAB1.xlsz",false],
            ["19082016100630_40075815_J1201508_TAB1.xlsx",true],
            ["19082016100630_40075815_J1201508_TAB1_.xlsx",false],
            ["19082016100632_40075815_J1201508_TAB2.xlsx",true],
            ["test.xlsx",false],
            ["testtab1.xlsx",false],
            ["testTAB1.xlsx",true]
        );
    }
    /**
     * тестирование на проверку верности файлов
     * @link https://www.nathankowald.com/blog/2012/12/how-to-unit-test-private-methods-in-php/ как тестировать приватные методы
     * @dataProvider datavalidFile
     */
    public function test_isValidFile($file,$res)
    {
        $l=new workWithFiles($file);
        $mes="Equals file name " .$file." res ".$res;
        $method = new \ReflectionMethod(workWithFiles::class, "isValidFile");
        $method->setAccessible(true);
        //$this->assertEquals($res, $l->isValidFile($file),$mes);
        $this->assertEquals($res, $method->invoke(new workWithFiles($file),$file),$mes);
        unset($l);

    }
}
