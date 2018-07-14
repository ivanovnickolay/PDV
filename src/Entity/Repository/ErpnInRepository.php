<?php

namespace App\Entity\Repository;
use App\Entity\forForm\search\allFromPeriod_Branch;
use App\Entity\forForm\search\docFromParam;
use App\Entity\forForm\search\getArrayFromSearch_Interface;
use App\Utilits\loadDataExcel\Exception\errorLoadDataException;
use App\Utilits\workToFileSystem\workWithFiles;


/**
 * ErpnIn
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ErpnInRepository extends \Doctrine\ORM\EntityRepository
{
	/**
	 * поиск данных в ЕРПН по условиям из класса getSearchAllFromPeriod_Branch
	 *
	 * @uses allFromPeriod_Branch класс поиска
	 * @uses allFromPeriod_Branch::getArrayFromSearchErpn возвращает данные для $arrayFromSearch
	 *
	 * использованные расширения
	 * @link  https://simukti.net/blog/2012/04/05/how-to-select-year-month-day-in-doctrine2/
	 * @link  https://github.com/beberlei/DoctrineExtensions
	 *
	 * @param getArrayFromSearch_Interface $arrayFromSearch
	 */
	public function getSearchAllFromPeriod_Branch($arrayFromSearch)
	{
		$emConfig = $this->getEntityManager()->getConfiguration();
		$emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
		$emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');

		$qr=$this->createQueryBuilder('ErpnIn');
		$qr->where('MONTH(ErpnIn.dateCreateInvoice)=:m');
		$qr->setParameter('m', $arrayFromSearch['monthCreateInvoice']);
		$qr->andWhere('YEAR(ErpnIn.dateCreateInvoice)=:y');
		$qr->setParameter('y', $arrayFromSearch['yearCreateInvoice']);

		if(array_key_exists('numBranchVendor', $arrayFromSearch))
		{
			$qr->andWhere('ErpnIn.numBranchVendor=:nbv');
			$qr->setParameter('nbv', $arrayFromSearch['numBranchVendor']);
		}

		if(array_key_exists('numMainBranch', $arrayFromSearch))
		{
			$qr->andWhere('ErpnIn.numMainBranch=:nmb');
			$qr->setParameter('nmb', $arrayFromSearch['numMainBranch']);
		}

		$result=$qr->getQuery();
		return $result->getResult();
	}

	/**
	 * @param $arrayFromSearch
	 * @return array
	 */
	public function getSearchAllFromParam($arrayFromSearch)
	{
		$emConfig = $this->getEntityManager()->getConfiguration();
		$emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
		$emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');

		$qr=$this->createQueryBuilder('ErpnIn');
		$qr->where('MONTH(ErpnIn.dateCreateInvoice)=:m');
		$qr->setParameter('m', $arrayFromSearch['monthCreateInvoice']);
		$qr->andWhere('YEAR(ErpnIn.dateCreateInvoice)=:y');
		$qr->setParameter('y', $arrayFromSearch['yearCreateInvoice']);


		if(array_key_exists('innClient', $arrayFromSearch))
		{
			$qr->andWhere('ErpnIn.innClient=:inn');
			$qr->setParameter('inn', $arrayFromSearch['innClient']);
		}

		if(array_key_exists('numInvoice', $arrayFromSearch))
		{
			$qr->andWhere('ErpnIn.numInvoice=:ni');
			$qr->setParameter('ni', $arrayFromSearch['numInvoice']);
		}

		if(array_key_exists('dateCreateInvoice', $arrayFromSearch))
		{
			$qr->andWhere('ErpnIn.dateCreateInvoice=:dсi');
			$qr->setParameter('dсi', $arrayFromSearch['dateCreateInvoice']);
		}

		$result=$qr->getQuery();
		return $result->getResult();
	}

    /**
     * Выполнение загрузки данных из *.csv файла при помощи команды
     * LOAD DATA LOCAL INFILE
     * @param string $fileName
     * @throws \Doctrine\DBAL\DBALException
     * @throws errorLoadDataException
     */
    public function loadDataInFile(string $fileName){
        $exstension = workWithFiles::getExtensionFileName($fileName);
        if ("csv" != $exstension) {
            throw new errorLoadDataException("Расширение файла $fileName не поддерживается. Расширение должно быть только csv ");
        }
        if (!file_exists($fileName)){
            throw new errorLoadDataException("Файл с данными для загрузки ЕРПН Полученные - не найден! Загрузка данных не возможна");
        }
        $testSQL = "LOAD DATA LOCAL INFILE :fileName
                    REPLACE INTO TABLE `Erpn_in` CHARACTER SET cp1251 FIELDS TERMINATED BY ';' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' 
                    LINES TERMINATED BY '\r\n' IGNORE 1 LINES 
                    (`num_invoice`, @date_create_invoice, @date_reg_invoice, `type_invoice_full`, 
                    `edrpou_client`, `inn_client`, `num_branch_client`, `name_client`, @suma_invoice, @pdvinvoice, 
                    @baza_invoice, `name_vendor`, `num_branch_vendor`, `num_reg_invoice`, `type_invoice`, `num_contract`, 
                    @date_contract, `type_contract`, `person_create_invoice`, `key_field`,`rke_info`) 
                    SET suma_invoice= IF(@suma_invoice='',0,REPLACE(@suma_invoice,',','.')),
                    pdvinvoice= IF(@pdvinvoice='',0,REPLACE(@pdvinvoice,',','.')),
                    baza_invoice= IF(@baza_invoice='',0,REPLACE(@baza_invoice,',','.')),
                    date_contract= IF(@date_contract='', NULL, STR_TO_DATE(@date_contract, '%d.%m.%Y')),
                    date_create_invoice= IF(@date_create_invoice='', NULL, STR_TO_DATE(@date_create_invoice, '%d.%m.%Y')),
                    date_reg_invoice= IF(@date_reg_invoice='', NULL, STR_TO_DATE(@date_reg_invoice, '%d.%m.%Y'))
                    ;
                    ALTER TABLE `Erpn_in` COLLATE='utf8_general_ci'; ";
        // Костыль для "разблокировки" возможности загрузки файлов
        // суть "костыля" - в создании абсолютно нового подключения к базе c ключем \PDO::MYSQL_ATTR_LOCAL_INFILE => true
        // https://stackoverflow.com/questions/18328594/an-exception-occurred-while-executing-load-data-local-infile?noredirect=1&lq=1
        $params  = $this->_em->getConnection()->getParams();
        $pdoConn = new \PDO('mysql:host=' .  $params['host'] . ';dbname=' . $params['dbname'], $params['user'], $params['password'], array(
            \PDO::MYSQL_ATTR_LOCAL_INFILE => true
        ));
        $smtp=$pdoConn->prepare($testSQL);
        $smtp ->bindValue("fileName",$fileName);
        $smtp->execute();
        unset($pdoConn,$smtp);
    }

    /** Поиск данных в таблице на основании параметров переданных в объекте docFromParam
     * @param docFromParam $param
     * @return mixed
     */
    public function searchDataFromParam(docFromParam $param){
        $objParam  = $param;
//        $emConfig = $this->getEntityManager()->getConfiguration();
//        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
//        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $qr=$this->createQueryBuilder('ErpnIn');

        $qr->where('MONTH(ErpnIn.dateCreateInvoice)=:m');
        $qr->setParameter('m',$objParam->getMonthCreate());

            $qr->andWhere('YEAR(ErpnIn.dateCreateInvoice)=:y');
            $qr->setParameter('y', $objParam->getYearCreate());

                $qr->andWhere('ErpnIn.typeInvoiceFull=:t');
                $qr->setParameter('t', $objParam->getTypeDoc());

                    if ($objParam->getDateCreateDoc()!=null){
                        $qr->andWhere('ErpnIn.dateCreateInvoice=:d');
                        $qr->setParameter('d', $objParam->getDateCreateDoc());
                    }

                    if ($objParam->getINN()!=0){
                        $qr->andWhere('ErpnIn.innClient=:i');
                        $qr->setParameter('i', $objParam->getINN());
                    }

                        if ($objParam->getNumDoc()!=0){
                            $qr->andWhere('ErpnIn.numInvoice=:ni');
                            $qr->setParameter('ni', $objParam->getNumDoc());
                        }

        $result=$qr->getQuery();
        return $result->getResult();

    }
}
