<?php
/**
 * Created by PhpStorm.
 * User: h-med
 * Date: 11/13/16
 * Time: 11:09 PM
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
class Product
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="productName", type="string", length=255)
     */
    private $productName;


//    /** One-To-Many, Unidirectional with Join Table
//     * @ManyToMany(targetEntity="Feature")
//     * @JoinTable(name="product_features",
//     *      joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
//     *      inverseJoinColumns={@JoinColumn(name="feature_id", referencedColumnName="id", unique=true)}
//     *      )
//     */

//    /**  Many-To-Many, Unidirectional
//     * @ManyToMany(targetEntity="Feature",cascade={"persist"})
//     * @JoinTable(name="product_features",
//     *      joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
//     *      inverseJoinColumns={@JoinColumn(name="feature_id", referencedColumnName="id")}
//     *      )
//     */

    /**  One-To-Many, Unidirectional
     * @ManyToMany(targetEntity="Feature",cascade={"persist"})
     *
     */
    private $features;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products",cascade={"persist"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\Column(name="createdAtDate", type="datetime")
     */
    protected $createdAtDate;


    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }


    /**
     * Product constructor .
     */

    public function __construct()
    {
        $this->createdAtDate = new DateTime();
        $this->features = new ArrayCollection();
    }

    public function getCreatedAtDate()
    {
        return $this->createdAtDate;
    }

    public function setCreatedAtDate(\DateTime $createdAtDate = null)
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

    /**
     * @return mixed
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * @param mixed $productName
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;
    }

    /**
     * @return mixed
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * @param mixed $features
     */
    public function setFeatures($feature)
    {
        $this->features->clear();
        if (!$this->features->contains($feature)) {
            $this->features->add($feature);
        }

    }
    //todo: got me to lot of trouble in form builder
    // $this->features = $features;


    public
    function addFeatures($feature)
    {
        $this->features->add($feature);
    }
}
