<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
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
        $products = "PRODUCTS";

        $features = $this->getAllFeatures();
        $products = $this->getAllProducts();

        return $this->render("products.html.twig", ['products' => $products, 'features' => $features]);
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

    public function addNewProduct($product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();
    }

    public function addSomeProducts()
    {
        $category = new Category();
        $category->setCategoryName('Desktop');


        $feature = new Feature();
        $feature->setFeatureName("oled");
        $feature0 = new Feature();
        $feature0->setFeatureName("lcd");
        $feature1 = new Feature();
        $feature1->setFeatureName("intel i5");
        $feature2 = new Feature();
        $feature2->setFeatureName("intel i3");

        $product = new Product();
        $product->setProductName("Desktop");
        $product->setCreatedAtDate(new \DateTime());
        $product->addFeatures($feature);
        $product->addFeatures($feature1);
        $product->setCategory($category);

        $product = new Product();
        $product->setProductName('Laptop');
        $product->addFeatures($feature0);
        $product->setCreatedAtDate(new \DateTime());
        $product->addFeatures($feature2);
        $product->setCategory($category);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        //  $em->persist($category);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();
    }

    /**
     * @return \AppBundle\Entity\Feature[]|array
     */
    public function getAllFeatures()
    {
        $features = $this->getDoctrine()->getManager()->getRepository('AppBundle:Feature')->findAll();
        return $features;
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











