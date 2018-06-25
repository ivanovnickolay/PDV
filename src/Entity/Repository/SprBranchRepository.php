<?php

namespace App\Entity\Repository;

/**
 * SprBranch
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */


use App\Entity\SprBranch;


/**
 * Class SprBranch
 * @package LoadFileBundle\Entity\Repository
 */
class SprBranchRepository extends \Doctrine\ORM\EntityRepository
//class SprBranchRepository extends ServiceEntityRepository
{
    /**
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SprBranch::class);
    }
     */

	/**
	 * @param $NumBranch
	 */
	public function findCountNumBranch($NumBranch)
    {

     	$smtp=$this->getEntityManager()->getConnection()->prepare("select count(id) AS cnt from SprBranch WHERE num_branch=:nb");
     	$smtp->bindValue("nb", $NumBranch);
     	$smtp->execute();
     	$result=$smtp->fetchAll();
	    if ($result[0]["cnt"]!=0) {
		    return true;
	    } else{
		    return false;
	    }


    }
}
