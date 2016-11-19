<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\ReceiptVoucher;
use AppBundle\Entity\Supplier;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Product;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ReceiptVoucherController extends Controller
{


    /**
     * @Route("/Receipts", name="receipts")
     */
    public function ReceiptsAction(Request $request)
    {
        // $this->addSomeSuppliers();
        //$this->addSomeReceipVoucher();

        $receipts = $this->getAllReceipts();

        return $this->render("receipts.html.twig", ['receipts' => $receipts]);
    }

    /**
     * @Route("/Receipts/{receipt_id}", name="receiptDetails")
     * @param integer $receipt_id
     * @return Response
     */
    public function receiptDetailsAction(Request $request, $receipt_id)
    {
        //$features = $this->getDoctrine()->getManager()->getRepository('AppBundle:Feature')->findAll();
        $receipt = $this->getDoctrine()->getManager()->getRepository('AppBundle:ReceiptVoucher')->find($receipt_id);

        if (!$receipt) {
            throw $this->createNotFoundException(
                'No receipt found for id ' . $receipt_id
            );
        }
        return $this->render("receipt_details.html.twig", ['receipt' => $receipt]);
    }

    /**
     * @Route("/receipt/new", name="add_receipt")
     */
    public function newReceiptAction(Request $request)
    {
        $defaultData = array('message' => 'Type your message here');
        $receipt = new ReceiptVoucher();
        $form = $this->createFormBuilder($receipt)
            ->add('receiptName', TextType::class)
//            ->add('productDescription', TextareaType::class)
//            ->add('productImage', FileType::class)
            ->add('createdAtDate', DateType::class)
//            ->add('createdAtDate', DateTimeType::class)
            ->add('supplier', ChoiceType::class, [
                'choices' => $this->getAllSuppliers(),
                'choice_label' => function ($supplier, $key, $index) {
                    /** @var Supplier $supplier */
                    return strtoupper($supplier->getSupplierName());
                },
            ])
//            ->add('category', ChoiceType::class, [
//                'choices' => $this->getAllCategories(),
//                'choice_label' => function ($Categories, $key, $index) {
//                    /** @var Category $Categories */
//                    return strtoupper($Categories->getCategoryName());
//                },
//            ])
            ->add('save', SubmitType::class, array('label' => 'Create Receipt'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $receipt = $form->getData();
            $products = $this->getAllProducts();

//            $receipt=new ReceiptVoucher();
            $em = $this->getDoctrine()->getManager();
            $prod2 = $em->getRepository('AppBundle:Product')->find(4);
            $prod1 = $em->getRepository('AppBundle:Product')->find(3);

//            $receipt->addProducts($prod1);
//            $receipt->addProducts($prod2);
            //$receipt->setProducts($products);
//          $ReceiptVouchers = $this->getDoctrine()->getManager()->getRepository('AppBundle:ReceiptVoucher')->findOneBy();
//            $receipt->receiptName="R0".$this->id;
            $this->addNewReceipt($receipt);

            return $this->redirectToRoute('receipts');
        }

        return $this->render('default/new_product.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/receipt/update/{receipt_id}", name="update_receipt")
     */
    public function updateReceiptAction(Request $request, $receipt_id)
    {
        $em = $this->getDoctrine()->getManager();
        $receipt = $em->getRepository('AppBundle:ReceiptVoucher')->find($receipt_id);

        if (!$receipt) {
            throw $this->createNotFoundException(
                'No Supplier found for id ' . $receipt_id
            );
        }


        $form = $this->createFormBuilder($receipt)
            ->add('receiptName', TextType::class)
//            ->add('productDescription', TextareaType::class)
//            ->add('productImage', FileType::class)
            ->add('createdAtDate', DateType::class)
//            ->add('createdAtDate', DateTimeType::class)
            ->add('supplier', ChoiceType::class, [
                'choices' => $this->getAllSuppliers(),
                'choice_label' => function ($supplier, $key, $index) {
                    /** @var Supplier $supplier */
                    return strtoupper($supplier->getSupplierName());
                },
            ])
//            ->add('category', ChoiceType::class, [
//                'choices' => $this->getAllCategories(),
//                'choice_label' => function ($Categories, $key, $index) {
//                    /** @var Category $Categories */
//                    return strtoupper($Categories->getCategoryName());
//                },
//            ])
            ->add('save', SubmitType::class, array('label' => 'Update Receipt'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $receipt = $form->getData();

            $em->flush();

            return $this->redirectToRoute('receiptDetails', ['receipt_id' => $receipt_id]);
        }

        return $this->render('default/new_Supplier.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/receipt/delete/{receipt_id}", name="delete_receipt")
     */
    public function deleteReceiptAction(Request $request, $receipt_id)
    {
        $em = $this->getDoctrine()->getManager();
        $receipt = $em->getRepository('AppBundle:ReceiptVoucher')->find($receipt_id);

        $em->remove($receipt);
        $em->flush();

        return $this->redirectToRoute('receipts');

    }

    /**
     * @Route("/receipt/{receipt_id}/add_product", name="add_product_to_receipt")
     */
    public function addProductToReceiptAction(Request $request, $receipt_id)
    {
        $em = $this->getDoctrine()->getManager();
        $receipt = $em->getRepository('AppBundle:ReceiptVoucher')->find($receipt_id);

        $product = new Product();
//        $product ->getCategory()->getCategoryName();

        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData)
//            ->add('productName', TextType::class)
            ->add('quantity', IntegerType::class)
//            ->add('createdAtDate', DateType::class)
            //->add('supplier', EntityType::class, ['class' => 'AppBundle:Supplier', 'choice_label' => 'supplierName'])
            ->add('product', ChoiceType::class, [
                'choices' => $this->getAllProducts(),
                'choice_label' => function ($product, $key, $index) {
                    /** @var Product $product */
                    return strtoupper($product->getProductName());
                },
            ])//$Categories
            ->add('save', SubmitType::class, array('label' => 'Add product'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $product_id=$data["product"]->getId();
            $quantity=$data["quantity"];
            $product = $em->getRepository('AppBundle:Product')->find($product_id);
            //todo: add quantity
            $product->setQuantity($quantity+$product->getQuantity());

            $this->addProductForReceipt($product,$receipt);
//            var_dump($product["quantity"]);
//            var_dump($product["product"]->getProductName());
//            var_dump($product["product"]->getId());
//            var_dump($product["product"]->getCategory()->getCategoryName());
//            die();
//            $this->addProductForSupplier($product, $supplier);
//            return $this->redirectToRoute('supplierDetails', ['supplier_id' => $supplier_id]);
            return $this->redirectToRoute('receiptDetails', ['receipt_id' => $receipt_id]);
        }

        return $this->render('default/new_product.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/supplier/{supplier_id}/update_product/{product_id}", name="update_product_by_supplier")
     */
    public function updateProductSupplierAction(Request $request, $supplier_id, $product_id)
    {

        $em = $this->getDoctrine()->getManager();
        $supplier = $em->getRepository('AppBundle:Supplier')->find($supplier_id);
        $product = $em->getRepository('AppBundle:Product')->find($product_id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $product_id
            );
        }


        $form = $this->createFormBuilder($product)
            ->add('productName', TextType::class)
            ->add('quantity', IntegerType::class)
            ->add('createdAtDate', DateType::class)
//            ->add('features', ChoiceType::class, [
//                'choices' => $this->getAllFeatures(),
//                'choice_label' => function ($features, $key, $index) {
//                    /** @var Feature $features */
//                    return strtoupper($features->getFeatureName());
//                },
//            ])
//            ->add('supplier', ChoiceType::class, [
//                'choices' => $this->getAllSuppliers(),
//                'choice_label' => function ($suppliers, $key, $index) {
//                    /** @var Supplier $suppliers */
//                    return strtoupper($suppliers->getSupplierName());
//                },
//            ])
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

            $this->updateProductForSupplier($product, $supplier);
//
//            return $this->redirectToRoute('products');
            return $this->redirectToRoute('supplierDetails', ['supplier_id' => $supplier_id]);
        }

        return $this->render('default/new_product.html.twig', array(
            'form' => $form->createView(),
        ));

    }
//delete_product_by_supplier
    /**
     * @Route("/supplier/{supplier_id}/delete/{product_id}", name="delete_product_by_supplier")
     * @param $product_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteProductSupplierAction($supplier_id, $product_id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($product_id);

        $em->remove($product);
        $em->flush();

//        return $this->redirectToRoute('products');
        return $this->redirectToRoute('supplierDetails', ['supplier_id' => $supplier_id]);

    }

    /////////////////////////////////// UTILS Functions ////////////////////////////////////////
    public function addNewReceipt($receipt)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($receipt);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();
    }

    public function addSomeSuppliers()
    {
        $category = new Category();
        $category->setCategoryName('Desktop');
        $category0 = new Category();
        $category0->setCategoryName('Desktop');

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
        $product0->setCategory($category0);
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
        // $em->persist($product);
        $em->persist($supplier0);
        $em->persist($supplier);
        //  $em->persist($category);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();
    }

    /**
     *
     */
    public function addSomeReceipVoucher()
    {

        $em = $this->getDoctrine()->getManager();
        $prod2 = $em->getRepository('AppBundle:Product')->find(4);
        $prod1 = $em->getRepository('AppBundle:Product')->find(3);
        $receipt = new ReceiptVoucher();
        $supplier = $em->getRepository('AppBundle:Supplier')->find(5);
        $receipt->setSupplier($supplier);
//        foreach ($prods as $p) {
        $receipt->addProducts($prod1);
        $receipt->addProducts($prod2);
//        }

        $receipt = $em->getRepository('AppBundle:ReceiptVoucher')->find(1);
        //$em->persist($receipt);
        // $em->remove($receipt);
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
     * @return \AppBundle\Entity\ReceiptVoucher[]|array
     */
    public function getAllReceipts()
    {
        $ReceiptVouchers = $this->getDoctrine()->getManager()->getRepository('AppBundle:ReceiptVoucher')->findAll();
        return $ReceiptVouchers;
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
     * @param Product $product
     * @param ReceiptVoucher $receipt
     * @internal param $supplier
     */
    public function addProductForReceipt(Product $product,ReceiptVoucher $receipt)
    {
//        $receipt=new ReceiptVoucher();

        //set supplier for the product.
//        $product->setSupplier($receipt);
        $receipt->addProducts($product);
        $em = $this->getDoctrine()->getManager();
        $em->persist($receipt);
        // actually executes the queries (i.e. the INSERT query)
        $em->flush();
    }

    /**
     * @param $product
     * @param $supplier
     */
    public function updateProductForSupplier($product, $supplier)
    {
        //set supplier for the product.
        $product->setSupplier($supplier);
        $em = $this->getDoctrine()->getManager();
        //$em->persist($product);
        // actually executes the queries (i.e. the INSERT query)
        $em->flush();
    }


}











