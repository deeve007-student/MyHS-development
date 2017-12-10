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

class LoadProductsData extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
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
        $excel = \PHPExcel_IOFactory::load($this->container->getParameter('kernel.root_dir') . '/../temp/fixtures/products.xlsx');

        foreach ($users as $user) {
            foreach ($excel->getWorksheetIterator() as $worksheet) {
                foreach ($worksheet->getRowIterator() as $row) {
                    if ($row->getRowIndex() > 1) {

                        $productName = $worksheet->getCellByColumnAndRow('A', $row->getRowIndex()) . '<br/>';
                        $productCode = $worksheet->getCellByColumnAndRow('B', $row->getRowIndex()) . '<br/>';
                        $productSupplier = $worksheet->getCellByColumnAndRow('C', $row->getRowIndex()) . '<br/>';
                        $productPrice = $worksheet->getCellByColumnAndRow('D', $row->getRowIndex()) . '<br/>';
                        $productCostPrice = $worksheet->getCellByColumnAndRow('E', $row->getRowIndex()) . '<br/>';

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
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}
