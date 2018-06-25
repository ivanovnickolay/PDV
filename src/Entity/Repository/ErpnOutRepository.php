<?php

namespace App\Entity\Repository;


/**
 * ErpnOut
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ErpnOutRepository extends \Doctrine\ORM\EntityRepository
{
	/**
	 * Проверка уникальности налоговой накладной в базе
	 * если проверка пройдена успешно возвращает true
	 * @param \App\Entity\ErpnOut|\App\Entity\Erpn_out $Invoice
	 * @return bool
	 */
	public function ValidInvoice(\App\Entity\ErpnOut $Invoice )
	{
		$num=$Invoice->getNumInvoice();
		$date=$Invoice->getDateCreateInvoice()->format('Y-m-d');
		$type=$Invoice->getTypeInvoiceFull();
		$inn=$Invoice->getInnClient();
		// Получаем количество записей по указанному условию
		if ($this->getCountKeyFields($num,$date,$type,$inn)== 0)
		{
			// если запесей нет то возвращаем истинно
			return true;
		} else
		{
			return false;
		}
	}


	/**
	 * Возвращеет количество записей по условию
	 * @param $numInvoice
	 * @param $dateInvoice
	 * @param $typeInvoice
	 * @param $INN
	 * @return mixed
	 */
	public function getCountKeyFields($numInvoice, $dateInvoice, $typeInvoice, $INN)
	{
		$qb = $this->createQueryBuilder("erpnOut");
		$qb->select($qb->expr()->count('erpn.id'));
		$qb->from('App:Erpn_out','erpn');
		$qb->where("erpn.NumInvoice=:num");
		$qb->andWhere("erpn.DateCreateInvoice=:d");
		$qb->andWhere("erpn.InnClient=:inn");
		$qb->andWhere("erpn.TypeInvoiceFull=:t");
		$qb->setParameter("num", $numInvoice);
		$qb->setParameter("d", $dateInvoice);
		$qb->setParameter("t", $typeInvoice);
		$qb->setParameter("inn", $INN);

		$count = $qb->getQuery()->getSingleScalarResult();

		return $count;
	}

	/**
	 * @param $file
	 */
	public function loadDataInfile($file){
		$db = $this->_em->getConnection();
		$sql = "LOAD DATA LOCAL INFILE $file REPLACE INTO TABLE `LoadFiles`.`Erpn_out`
CHARACTER SET cp1251 FIELDS TERMINATED BY ';' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\r\n'
IGNORE 1
LINES (`num_invoice`, `date_create_invoice`, `date_reg_invoice`, `type_invoice_full`,
`edrpou_client`, `inn_client`, `num_branch_client`, `name_client`, @suma_invoice, @pdvinvoice,
@baza_invoice, `name_vendor`, `num_branch_vendor`, `num_reg_invoice`, `type_invoice`, `num_contract`,
@date_contract, `type_contract`, `person_create_invoice`, `key_field`)
SET suma_invoice=IF(@suma_invoice='',0,REPLACE(@suma_invoice,',','.')),
pdvinvoice= IF(@pdvinvoice='',0,REPLACE(@pdvinvoice,',','.')),
baza_invoice=IF(@baza_invoice='',0,REPLACE(@baza_invoice,',','.')),
date_contract=IF(@date_contract='',NULL,@date_contract)\";";
		$stmt = $db->prepare($sql);
		$stmt->execute();
	}

	/**
	 * поиск данных в ЕРПН по условиям из класса getSearchAllFromPeriod_Branch
	 *
	 * @uses allFromPeriod_Branch класс поиска
	 * @uses allFromPeriod_Branch::getArrayFromSearchErpn возвращает данные для $arrayFromSearch
	 *
	 *
	 * @param getArrayFromSearch_Interface $arrayFromSearch
	 * @return array
	 */
	public function getSearchAllFromPeriod_Branch($arrayFromSearch)
	{

		$qr=$this->createQueryBuilder('ErpnOut');
		$qr->where('ErpnOut.monthCreateInvoice=:m');
		$qr->setParameter('m', $arrayFromSearch['monthCreateInvoice']);
		$qr->andWhere('ErpnOut.yearCreateInvoice=:y');
		$qr->setParameter('y', $arrayFromSearch['yearCreateInvoice']);

		if(array_key_exists('numBranchVendor', $arrayFromSearch))
		{
			$qr->andWhere('ErpnOut.numBranchVendor=:nbv');
			$qr->setParameter('nbv', $arrayFromSearch['numBranchVendor']);
		}

		if(array_key_exists('numMainBranch', $arrayFromSearch))
		{
			$qr->andWhere('ErpnOut.numMainBranch=:nmb');
			$qr->setParameter('nmb', $arrayFromSearch['numMainBranch']);
		}

		$result=$qr->getQuery();
		return $result->getResult();
	}

	/**
	 * поиск данных в ЕРПН по условиям из класса getSearchAllFromPeriod_Branch
	 *
	 * @uses allFromPeriod_Branch класс поиска
	 * @uses allFromPeriod_Branch::getArrayFromSearchErpn возвращает данные для $arrayFromSearch
	 *
	 *
	 * @param getArrayFromSearch_Interface $arrayFromSearch
	 * @return array
	 */
	public function getSearchAllFromParam($arrayFromSearch)
	{

		$qr=$this->createQueryBuilder('ErpnOut');
		$qr->where('ErpnOut.monthCreateInvoice=:m');
		$qr->setParameter('m', $arrayFromSearch['monthCreateInvoice']);
		$qr->andWhere('ErpnOut.yearCreateInvoice=:y');
		$qr->setParameter('y', $arrayFromSearch['yearCreateInvoice']);
		$qr->andWhere('ErpnOut.typeInvoiceFull=:t');
		$qr->setParameter('t', $arrayFromSearch['typeInvoiceFull']);


		if(array_key_exists('innClient', $arrayFromSearch))
		{
			$qr->andWhere('ErpnOut.innClient=:inn');
			$qr->setParameter('inn', $arrayFromSearch['innClient']);
		}

		if(array_key_exists('numInvoice', $arrayFromSearch))
		{
			$qr->andWhere('ErpnOut.numInvoice=:ni');
			$qr->setParameter('ni', $arrayFromSearch['numInvoice']);
		}

		if(array_key_exists('dateCreateInvoice', $arrayFromSearch))
		{
			$qr->andWhere('ErpnOut.dateCreateInvoice=:dсi');
			$qr->setParameter('dсi', $arrayFromSearch['dateCreateInvoice']);
		}

		$result=$qr->getQuery();
		return $result->getResult();
	}

	/**
	 * @param $month
	 * @param $year
	 * @param $numBranch
	 * @param $inn
	 * @return array
	 */
	public function getAnalizData(int $month, int $year, string $numBranch, string $inn)
	{
		$SQL="Select num_invoice, 
					date_format(date_create_invoice,'%d.%m.%Y'),
					type_invoice_full,
					inn_client,
					 name_client,
					 pdvinvoice,
					 name_vendor
			  from erpn_out
			  where month_create_invoice=:m AND 
			  		year_create_invoice=:y AND 
			  		num_main_branch=:nb AND 
			  		inn_client=:inn";
		$smtp=$this->_em->getConnection();
		$qr=$smtp->prepare($SQL);
		$qr->bindParam("m", $month);
		$qr->bindParam("y", $year);
		$qr->bindParam("nb", $numBranch);
		$qr->bindParam("inn", $inn);
		$qr->execute();
		$arrayResult=$qr->fetchAll();
		return $arrayResult;

	}


	/**
	 * Получение документов из ЕРПН
	 *
	 * @param $month
	 * @param $year
	 * @param $numBranch
	 * @param $INN
	 * @return array
	 * @internal param $array
	 */
	public function getDocFromERPN($month, $year,$numBranch, $INN)
	{
		$qr=$this->createQueryBuilder('ErpnOut');
		$qr->where('ErpnOut.monthCreateInvoice=:m');
		$qr->setParameter('m', $month);
		$qr->andWhere('ErpnOut.yearCreateInvoice=:y');
		$qr->setParameter('y', $year);
		$qr->andWhere('ErpnOut.innClient=:inn');
		$qr->setParameter('inn', $INN);
		$qr->andWhere('ErpnOut.numMainBranch=:nmb');
		$qr->setParameter('nmb', $numBranch);

		$result=$qr->getQuery();
		return $result->getResult();

	}
}
