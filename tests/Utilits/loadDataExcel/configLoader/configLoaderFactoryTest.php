<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 21.02.2018
 * Time: 22:23
 */

namespace App\Utilits\loadDataExcel\configLoader;

use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\{
    TestCase
};

class configLoaderFactoryTest extends TestCase
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
                'test_tab1.xls',
                'test1___2tab2.xlsx',
                'test1___2tab2fgldghl.xlsx',
                'test1___2TAB2fgldghl.xlsx',
                'test1___2TAB2.xlsx',
                'test1___2TAB1.xlsx',
            )
        ];
        // setup and cache the virtual file system
        $this->file_system = vfsStream::setup('root', 444, $directory);
    }

    /**
     * @throws errorLoadDataException
     */
    public function testGetConfigLoad_1()
    {
        $this->expectException(errorLoadDataException::class);
        $this->expectExceptionMessage("Для файла vfs://roottest_tab1.xls не существует конфигурации для чтения информации из файла !");
        $obj = configLoaderFactory::getConfigLoad($this->file_system->url().'test_tab1.xls');
        $this->assertInstanceOf(configLoader_interface::class,$obj);
    }

    /**
     * тестирование бросания исключения
     * @throws errorLoadDataException
     */
    public function testGetConfigLoad_2()
    {
        $this->expectException(errorLoadDataException::class);
        $obj = configLoaderFactory::getConfigLoad($this->file_system->url().'test1___2tab2fgldghl.xls');
        $this->assertInstanceOf(configLoader_interface::class,$obj);
    }
    /**
     * тестирование бросания исключения и сообщения
     * @throws errorLoadDataException
     */
    public function testGetConfigLoad_3()
    {
        $this->expectException(errorLoadDataException::class);
        $this->expectExceptionMessage('Для файла vfs://roottest1___2TAB2fgldghl.xls не существует конфигурации для чтения информации из файла !');
        $obj = configLoaderFactory::getConfigLoad($this->file_system->url().'test1___2TAB2fgldghl.xls');
        $this->assertInstanceOf(configLoader_interface::class,$obj);
    }

    /**
     * тест с верным названием файла на возвращение коонфигуратора нужного типа
     * @throws errorLoadDataException
     */
    public function testGetConfigLoad_ReestrOut()
    {
        //$this->expectException(errorLoadDataException::class);
        $obj = configLoaderFactory::getConfigLoad($this->file_system->url().'test1___2TAB2.xls');
        $this->assertInstanceOf(configLoader_interface::class,$obj);
        $this->assertInstanceOf(configLoadReestrOut::class,$obj);
    }

    /**
     * тест с верным названием файла на возвращение коонфигуратора нужного типа
     * @throws errorLoadDataException
     */
    public function testGetConfigLoad_ReestrIn()
    {
        //$this->expectException(errorLoadDataException::class);
        $obj = configLoaderFactory::getConfigLoad($this->file_system->url().'test1___2TAB1.xls');
        $this->assertInstanceOf(configLoader_interface::class,$obj);
        $this->assertInstanceOf(configLoadReestrIn::class,$obj);
    }
}
