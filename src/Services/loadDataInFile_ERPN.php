<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 29.06.2018
 * Time: 00:39
 */

namespace App\Services;

use App\Entity\ErpnIn;
use App\Entity\ErpnOut;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use App\Utilits\workToFileSystem\workWithFiles;
use Doctrine\ORM\EntityManagerInterface;


/**
 * Class loadDataInFile_ERPN - класс для организации загрузки данных из файлов в таблицы базы данных
 *  - ЕРПН Выданные
 *  - ЕРПН Полученные
 * @package Services
 *
 */
class loadDataInFile_ERPN{

    private $em;

    private $dirForFilesERPN_In;

    private $dirForFilesERPN_Out;

    /**
     * инициализация класса для организации загрузки данных из файлов в таблицы базы данных
     *  - ЕРПН Выданные
     *  - ЕРПН Полученные
     * loadDataInFile_ERPN constructor.
     * @param EntityManagerInterface $em
     * @param string $dirForFilesERPN_In - директория для файлов, данные из которых загружаются в ЕРПН Полученные
     * @param string $dirForFilesERPN_Out - директория для файлов, данные из которых загружаются в ЕРПН Выданные
     * @throws errorLoadDataException  - при отсутствии директорий
     */
    public function __construct(EntityManagerInterface $em, string $dirForFilesERPN_In, string $dirForFilesERPN_Out){
        $this->em = $em;
        if (!is_dir($dirForFilesERPN_In)){
            throw new errorLoadDataException("Директория для загрузки данных в таблицу ЕРПН Полученные (кредит) не найдена.");
        }
            if (!is_dir($dirForFilesERPN_Out)){
                throw new errorLoadDataException("Директория для загрузки данных в таблицу ЕРПН Выданные (обязательства)не найдена.");
            }
                $this->dirForFilesERPN_Out=$dirForFilesERPN_Out;
                    $this->dirForFilesERPN_In = $dirForFilesERPN_In;
    }

    /**
     * Организация загрузки данных их файла в таблицу ЕРПН Полученные (кредит)
     * @param string $fileName
     * @throws \Doctrine\DBAL\DBALException
     * @throws errorLoadDataException
     */
    public function load_ERPN_In(string $fileName){
        $this->validExtensions($fileName);
        if (!file_exists($this->dirForFilesERPN_In.$fileName)){
            throw new errorLoadDataException("Файл с данными $fileName для загрузки в таблицу ЕРПН Полученные (кредит) не найден");
        }
        $this->em->getRepository(ErpnIn::class)->loadDataInFile($this->dirForFilesERPN_In.$fileName);

    }

    /**
     * Организация загрузки данных их файла в таблицу ЕРПН Полученные (кредит)
     * @param string $fileName
     * @throws \Doctrine\DBAL\DBALException
     * @throws errorLoadDataException
     */
    public function load_ERPN_Out(string $fileName){
        $this->validExtensions($fileName);
        if (!file_exists($this->dirForFilesERPN_Out.$fileName)){
            throw new errorLoadDataException("Файл с данными $fileName для загрузки в таблицу ЕРПН Выданные (обязательства) не найден");
        }
        $this->em->getRepository(ErpnOut::class)->loadDataInFile($this->dirForFilesERPN_Out.$fileName);

    }

    /**
     * @param string $fileName
     * @throws errorLoadDataException
     */
    private function validExtensions(string $fileName): void
    {
       $exstension = workWithFiles::getExtensionFileName($fileName);
        if ("csv" != $exstension) {
            throw new errorLoadDataException("Расширение файла $fileName не поддерживается. Расширение должно быть только csv ");
        }
    }

}