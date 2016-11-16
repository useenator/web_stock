<?php

/**
 * Created by PhpStorm.
 * User: h-med
 * Date: 11/15/16
 * Time: 5:34 AM
 */
namespace AppBundle\Entity;


use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;

/** @Entity */
class Supplier
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="supplierName", type="string", length=255)
     */
    private $supplierName;


//    /** One-To-Many, Unidirectional with Join Table
//     * @ManyToMany(targetEntity="Feature")
//     * @JoinTable(name="product_features",
//     *      joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
//     *      inverseJoinColumns={@JoinColumn(name="feature_id", referencedColumnName="id", unique=true)}
//     *      )
//     */

//    /** One-To-Many, Unidirectional with Join Table
//     * @ManyToMany(targetEntity="Product",cascade={"persist"})
//     * @JoinTable(name="supplier_product",
//     *      joinColumns={@JoinColumn(name="supplier_id", referencedColumnName="id")},
//     *      inverseJoinColumns={@JoinColumn(name="product_id", referencedColumnName="id", unique=true)}
//     *      )
//     */

//    /**  One-To-Many, Unidirectional
//     * @ManyToMany(targetEntity="Product",cascade={"persist"})
//     *
//     */

    /**
     * @OneToMany(targetEntity="Product", mappedBy="supplier",cascade={"persist"})
     */
    private $products;


    /**
     * @ORM\Column(name="createdAtDate", type="datetime")
     */
    protected $createdAtDate;


    /**
     * Product constructor .
     */

    public function __construct()
    {
        $this->createdAtDate = new DateTime();
        $this->products = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getSupplierName()
    {
        return $this->supplierName;
    }

    /**
     * @param mixed $supplierName
     */
    public function setSupplierName($supplierName)
    {
        $this->supplierName = $supplierName;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @param mixed $product
     */
    public function addProducts($product)
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

    }

    /**
     * @return mixed
     */
    public function getCreatedAtDate()
    {
        return $this->createdAtDate;
    }

    /**
     * @param mixed $createdAtDate
     */
    public function setCreatedAtDate($createdAtDate)
    {
        $this->createdAtDate = $createdAtDate;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

}