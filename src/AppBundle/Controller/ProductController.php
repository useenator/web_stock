<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Supplier;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Feature;
use AppBundle\Entity\Product;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ProductController extends Controller
{


    /**
     * @Route("/products", name="products")
     */
    public function productsAction(Request $request)
    {
   // $this->addSomeProducts();

        $products = $this->getAllProducts();

        return $this->render("products.html.twig", ['products' => $products]);
    }

    /**
     * @Route("/products/{product_id}", name="productDetails")
     * @param integer $product_id
     */
    public function productDetailsAction(Request $request, $product_id)
    {
        //$features = $this->getDoctrine()->getManager()->getRepository('AppBundle:Feature')->findAll();
        $product = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->find($product_id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $product_id
            );
        }
        return $this->render("product_details.html.twig", ['product' => $product]);
    }

    /**
     * @Route("/new", name="add_product")
     */
    public function newAction(Request $request)
    {
        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData)
            ->add('productName', TextType::class)
//            ->add('productDescription', TextareaType::class)
//            ->add('productImage', FileType::class)
            ->add('createdAtDate', DateType::class)
//            ->add('createdAtDate', DateTimeType::class)
//            ->add('features', ChoiceType::class, [
//                'choices' => $this->getAllFeatures(),
//                'choice_label' => function ($feature, $key, $index) {
//                    /** @var Feature $feature */
//                    return strtoupper($feature->getFeatureName());
//                },
//            ])
            ->add('supplier', ChoiceType::class, [
                'choices' => $this->getAllSuppliers(),
                'choice_label' => function ($supplier, $key, $index) {
                    /** @var Supplier $supplier */
                    return strtoupper($supplier->getSupplierName());
                },
            ])
            ->add('category', ChoiceType::class, [
                'choices' => $this->getAllCategories(),
                'choice_label' => function ($Categories, $key, $index) {
                    /** @var Category $Categories */
                    return strtoupper($Categories->getCategoryName());
                },
            ])//$Categories
            ->add('save', SubmitType::class, array('label' => 'Create product'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $product = new Product();
            $product->setProductName($data['productName']);
            $product->setCreatedAtDate($data['createdAtDate']);
            $product->setSupplier($data['supplier']);
//            $product->addFeature($data['features']);
            $product->setCategory($data['category']);

            $this->addNewProduct($product);

            return $this->redirectToRoute('products');
        }

        return $this->render('default/new_product.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/update/{product_id}", name="update_product")
     */
    public function updateAction(Request $request, $product_id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($product_id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $product_id
            );
        }


        $form = $this->createFormBuilder($product)
            ->add('productName', TextType::class)
            ->add('createdAtDate', DateType::class)
//            ->add('features', ChoiceType::class, [
//                'choices' => $this->getAllFeatures(),
//                'choice_label' => function ($features, $key, $index) {
//                    /** @var Feature $features */
//                    return strtoupper($features->getFeatureName());
//                },
//            ])
            ->add('supplier', ChoiceType::class, [
                'choices' => $this->getAllSuppliers(),
                'choice_label' => function ($suppliers, $key, $index) {
                    /** @var Supplier $suppliers */
                    return strtoupper($suppliers->getSupplierName());
                },
            ])
            ->add('category', ChoiceType::class, [
                'choices' => $this->getAllCategories(),
                'choice_label' => function ($Categories, $key, $index) {
                    /** @var Category $Categories */
                    return strtoupper($Categories->getCategoryName());
                },
            ])//$Categories
            ->add('save', SubmitType::class, array('label' => 'Update product'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $em->flush();

            return $this->redirectToRoute('products');
        }

        return $this->render('default/new_product.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/delete/{product_id}", name="delete_product")
     */
    public function deleteAction(Request $request, $product_id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($product_id);

        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('products');

    }


    /////////////////////////////////// UTILS Functions ////////////////////////////////////////
    public function addNewProduct($product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();
    }

    public function addSomeProducts()
    {

        $supplier = new Supplier();
        $supplier->setSupplierName("supplier 1");
        $supplier0 = new Supplier();
        $supplier0->setSupplierName("supplier 2");

        $category = new Category();
        $category->setCategoryName('Desktop');
        $category0 = new Category();
        $category0->setCategoryName('Laptop');

//
//        $feature = new Feature();
//        $feature->setFeatureName("oled");
//        $feature0 = new Feature();
//        $feature0->setFeatureName("lcd");
//        $feature1 = new Feature();
//        $feature1->setFeatureName("intel i5");
//        $feature2 = new Feature();
//        $feature2->setFeatureName("intel i3");

//        $em = $this->getDoctrine()->getManager();
//        $em->persist($product);
//        //  $em->persist($category);
//
//        // actually executes the queries (i.e. the INSERT query)
//        $em->flush();

        $product0 = new Product();
        $product0->setProductName("Desktop");
        $product0->setCreatedAtDate(new \DateTime());

        $product0->setCategory($category0);
        $product0->setSupplier($supplier);

        $product = new Product();
        $product->setProductName('Laptop');

        $product->setCreatedAtDate(new \DateTime());



        $product->setCategory($category);
        $product->setSupplier($supplier0);

        $product1 = new Product();
        $product1->setProductName('Laptop');
//        $product1->addFeature($feature0);
        $product1->setCreatedAtDate(new \DateTime());
//        $product1->addFeature($feature2);
        $product1->setCategory($category);
        $product1->setSupplier($supplier0);

        $supplier->addProducts($product);
        $supplier->addProducts($product0);
        $supplier0->addProducts($product1);

        $em = $this->getDoctrine()->getManager();
       //  $em->persist($product);
        $em->persist($supplier0);
        $em->persist($supplier);
        //  $em->persist($category);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();
    }



    /**
     * @return \AppBundle\Entity\Product[]|array
     */
    public function getAllProducts()
    {
        $products = $this->getDoctrine()->getManager()->getRepository('AppBundle:Product')->findAll();
        return $products;
    }

    public function getAllCategories()
    {
        $Categories = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category')->findAll();
        return $Categories;
    }

    /**
     * @return \AppBundle\Entity\Supplier[]|array
     */
    public function getAllSuppliers()
    {
        $suppliers = $this->getDoctrine()->getManager()->getRepository('AppBundle:Supplier')->findAll();
        return $suppliers;
    }


}











