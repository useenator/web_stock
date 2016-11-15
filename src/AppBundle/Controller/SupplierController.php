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


class SupplierController extends Controller
{


    /**
     * @Route("/suppliers", name="suppliers")
     */
    public function suppliersAction(Request $request)
    {
         //$this->addSomeSuppliers();
        $products = "PRODUCTS";

//        $features = $this->getAllFeatures();
        $suppliers = $this->getAllSuppliers();

        return $this->render("suppliers.html.twig", [ 'suppliers' => $suppliers]);
    }

    /**
     * @Route("/suppliers/{supplier_id}", name="supplierDetails")
     * @param integer $supplier_id
     */
    public function supplierDetailsAction(Request $request, $supplier_id)
    {
        //$features = $this->getDoctrine()->getManager()->getRepository('AppBundle:Feature')->findAll();
        $supplier = $this->getDoctrine()->getManager()->getRepository('AppBundle:Supplier')->find($supplier_id);

        if (!$supplier) {
            throw $this->createNotFoundException(
                'No product found for id ' . $product_id
            );
        }
        return $this->render("supplier_details.html.twig", ['supplier' => $supplier]);
    }

    /**
     * @Route("/supplier/new", name="add_supplier")
     */
    public function newSupplierAction(Request $request)
    {
        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData)
            ->add('productName', TextType::class)
            ->add('productDescription', TextareaType::class)
            ->add('productImage', FileType::class)
            ->add('createdAtDate', DateType::class)
//            ->add('createdAtDate', DateTimeType::class)
            ->add('feature', ChoiceType::class, [
                'choices' => $this->getAllFeatures(),
                'choice_label' => function ($feature, $key, $index) {
                    /** @var Feature $feature */
                    return strtoupper($feature->getFeatureName());
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
            $product->addFeatures($data['feature']);
            $product->setCategory($data['category']);

            $this->addNewProduct($product);

            return $this->redirectToRoute('products');
        }

        return $this->render('default/new_product.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/supplier/update/{$supplier_id}", name="update_supplier")
     */
    public function updateSupplierAction(Request $request, $supplier_id)
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
            ->add('features', ChoiceType::class, [
                'choices' => $this->getAllFeatures(),
                'choice_label' => function ($feature, $key, $index) {
                    /** @var Feature $feature */
                    return strtoupper($feature->getFeatureName());
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
     * @Route("/supplier/delete/{$supplier_id}", name="delete_product")
     */
    public function deleteSupplierAction(Request $request, $supplier_id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($product_id);

        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('products');

    }


    /////////////////////////////////// UTILS Functions ////////////////////////////////////////
    public function addNewSupplier($suppliert)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($suppliert);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();
    }

    public function addSomeSuppliers()
    {
        $category = new Category();
        $category->setCategoryName('Desktop');

        $supplier = new Supplier();
        $supplier->setSupplierName("supplier 1");


        $supplier0 = new Supplier();
        $supplier0->setSupplierName("supplier 2");


        $feature = new Feature();
        $feature->setFeatureName("oled");
        $feature0 = new Feature();
        $feature0->setFeatureName("lcd");
        $feature1 = new Feature();
        $feature1->setFeatureName("intel i5");
        $feature2 = new Feature();
        $feature2->setFeatureName("intel i3");

        $product0 = new Product();
        $product0->setProductName("Desktop");
        $product0->setCreatedAtDate(new \DateTime());
        $product0->addFeatures($feature);
        $product0->addFeatures($feature1);
        $product0->setCategory($category);
        $product0->setSupplier($supplier);

        $product = new Product();
        $product->setProductName('Laptop');
        $product->addFeatures($feature0);
        $product->setCreatedAtDate(new \DateTime());
        $product->addFeatures($feature2);
        $product->setCategory($category);
        $product->setSupplier($supplier0);

        $product1 = new Product();
        $product1->setProductName('Laptop');
        $product1->addFeatures($feature0);
        $product1->setCreatedAtDate(new \DateTime());
        $product1->addFeatures($feature2);
        $product1->setCategory($category);
        $product1->setSupplier($supplier0);

        $supplier->addProducts($product);
        $supplier->addProducts($product0);
        $supplier0->addProducts($product1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->persist($supplier0);
        $em->persist($supplier);
        //  $em->persist($category);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();
    }

    /**
     * @return \AppBundle\Entity\Supplier[]|array
     */
    public function getAllSuppliers()
    {
        $suppliers = $this->getDoctrine()->getManager()->getRepository('AppBundle:Supplier')->findAll();
        return $suppliers;
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


}











