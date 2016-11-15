<?php
//
///*
// * For the full copyright and license information, please view the LICENSE file
// * that was distributed with this source code.
// */
//
//namespace AppBundle\Entity;
//
//use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\ORM\Mapping as ORM;
//
///** @Entity */
//class Product
//{
//    /**
//     * @ORM\Column(name="id", type="integer")
//     * @ORM\Id
//     * @ORM\GeneratedValue(strategy="AUTO")
//     */
//    private $id;
//
//    /**
//     * @ORM\Column(name="productName", type="string", length=255)
//     */
//    private $productName;
//
//    /**
//     * @OneToMany(targetEntity="Feature", mappedBy="product")
//     */
//
//    private $features;
//
//    // ...
//
//    public function __construct()
//    {
//        $this->features = new ArrayCollection();
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getProductName()
//    {
//        return $this->productName;
//    }
//
//    /**
//     * @param mixed $productName
//     */
//    public function setProductName($productName)
//    {
//        $this->productName = $productName;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getFeatures()
//    {
//        return $this->features;
//    }
//
//    /**
//     * @param mixed $features
//     */
//    public function setFeatures($features)
//    {
//        $this->features = $features;
//    }
//}
//
///** @Entity */
//class Feature
//{
//    /**
//     * @ORM\Column(name="id", type="integer")
//     * @ORM\Id
//     * @ORM\GeneratedValue(strategy="AUTO")
//     */
//    private $id;
//
//    /**
//     * @ORM\Column(name="featureName", type="string", length=255)
//     */
//    private $featureName;
//
//    /**
//     * @ManyToOne(targetEntity="Product", inversedBy="features")
//     * @JoinColumn(name="product_id", referencedColumnName="id")
//     */
//    private $product;
//
//    /**
//     * @return mixed
//     */
//    public function getFeatureName()
//    {
//        return $this->featureName;
//    }
//
//    /**
//     * @param mixed $featureName
//     */
//    public function setFeatureName($featureName)
//    {
//        $this->featureName = $featureName;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getProduct()
//    {
//        return $this->product;
//    }
//
//    /**
//     * @param mixed $product
//     */
//    public function setProduct($product)
//    {
//        $this->product = $product;
//    }
//    // ...
//
//}