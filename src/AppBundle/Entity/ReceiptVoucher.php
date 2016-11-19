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
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/** @Entity */
class ReceiptVoucher
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

//    /**
//     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Supplier")
//     */,cascade={"persist", "remove"}

    /**
     * @ORM\Column(name="receiptName", type="string", length=255)
     */
    private $receiptName;

    /**
     * @ManyToOne(targetEntity="Supplier")
     */
    private $supplier;



    //todo: ManyToMany with removal of orphan receipt_voucher_product table
    /**  One-To-Many, Unidirectional
     *
     * @ManyToMany(targetEntity="Product", orphanRemoval=true)
     * @JoinTable(name="receipt_voucher_product",
     *      joinColumns={@JoinColumn(name="receipt_voucher_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="product_id", referencedColumnName="id")}
     *      )
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

//        $ReceiptVouchers = $this->getDoctrine()->getManager()->getRepository('AppBundle:ReceiptVoucher')->fi();
//        $this->receiptName="R0".$this->id;
    }

    /**
     * @return mixed
     */
    public function getReceiptName()
    {
        return $this->receiptName;
    }

    /**
     * @param mixed $receiptName
     */
    public function setReceiptName($receiptName)
    {
        $this->receiptName = $receiptName;
    }

    /**
     * @return mixed
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * @param mixed $supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
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