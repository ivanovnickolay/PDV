<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 04.09.2016
 * Time: 21:06
 */

namespace App\Utilits\loadDataExcel\loadData;


use App\Utilits\ValidEntity\validReestrIn;
use Doctrine\ORM\EntityManager;

/**
 * @deprecated
 * Class factoryLoadData
 * @package LoadDataExcelBundle\Util\loadData
 */
class factoryLoadData
{
	private $em;

	private $file;

	private $type;

	private $loaderClass;

 public function __construct (EntityManager $em)
 {
	 $this->em=$em;
 }
	public function __destruct ()
	{
		unset($this->loaderClass);
	}

	/**
	 * @param $file
	 * @param $type
	 */
	public function loadDataFromFile($file, $type)
	{
		$this->file=$file;
		$this->type=$type;
		$this->loaderClass=$this->getLoaderClass();
		//$this->loaderClass->loadData();
		//unset($this->loaderClass);
	}

	/**
	 * на основании значение $type формируем данные для загрузчина файлов loadData
	 * и возвращаем класс loadData готовый для загрузки файла
	 * @return loadData
	 */
	private function getLoaderClass()
	{
		switch ($this->type)
		{
			case "RestrIn":
				$loaderClass=new loadData($this->em,$this->file,'EE',1000);
				$db=new createReestrIn();
				//$loaderClass->setEntity($db);
				//$loaderClass->setValidator(new validReestrIn('In'));
				$loaderClass->loadData();
				unset($db,$loaderClass);
				break;
			case "RestrOut":
				$loaderClass=new loadData($this->em,$this->file,'DR',1000);
				$db=new createReestrOut();
				//$loaderClass->setEntity($db);
				//$loaderClass->setValidator(new validReestrIn('Out'));
				$loaderClass->loadData();
				unset($db,$loaderClass);
				break;
		}
	}
}