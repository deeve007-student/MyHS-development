<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 13:05
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadProductsData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository('UserBundle:User')->findAll();
        $file = $this->container->getParameter('kernel.root_dir') . '/../temp/fixtures/products.xlsx';
        $inputFileType = \PHPExcel_IOFactory::identify($file);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($file);
        $worksheet = $objPHPExcel->setActiveSheetIndex(0);

        foreach ($users as $user) {
            foreach ($worksheet->getRowIterator() as $row) {
                if ($row->getRowIndex() > 1) {

                    $productName = $worksheet->getCell('A' . $row->getRowIndex())->getValue();
                    $productCode = $worksheet->getCell('B' . $row->getRowIndex())->getValue();
                    $productSupplier = $worksheet->getCell('C' . $row->getRowIndex())->getValue();
                    $productPrice = $worksheet->getCell('D' . $row->getRowIndex())->getValue();
                    $productCostPrice = $worksheet->getCell('E' . $row->getRowIndex())->getValue();

                    $product = new Product();
                    $product->setName($productName)
                        ->setCostPrice($productCostPrice)
                        ->setSupplier($productSupplier)
                        ->setCode($productCode)
                        ->setPrice($productPrice)
                        ->setOwner($user);

                    $manager->persist($product);

                }
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}
