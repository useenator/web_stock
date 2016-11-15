<?php
/**
 * Created by PhpStorm.
 * User: h-med
 * Date: 11/13/16
 * Time: 11:09 PM
 */


/*
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/** @Entity */
class Feature
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="featureName", type="string", length=255)
     */
    private $featureName;

//    /**
//     * @ManyToOne(targetEntity="Product", inversedBy="features")
//     * @JoinColumn(name="product_id", referencedColumnName="id")
//     */
//    private $product;


    /**
     * @return mixed
     */
    public function getFeatureName()
    {
        return $this->featureName;
    }

    /**
     * @param mixed $featureName
     */
    public function setFeatureName($featureName)
    {
        $this->featureName = $featureName;
    }

//    /**
//     * @return mixed
//     */
//    public function getProduct()
//    {
//        return $this->product;
//    }
//    /**
//     * @param mixed $product
//     */
//    public function setProduct($product)
//    {
//        $this->product = $product;
//    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    // ...

}