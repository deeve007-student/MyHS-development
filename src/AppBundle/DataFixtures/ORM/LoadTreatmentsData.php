<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 13.03.2017
 * Time: 13:05
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Treatment;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadTreatmentsData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $file = $this->container->getParameter('kernel.root_dir') . '/../temp/fixtures/treatments.xlsx';
        $inputFileType = \PHPExcel_IOFactory::identify($file);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($file);
        $worksheet = $objPHPExcel->setActiveSheetIndex(0);

        $replaceColor = function ($colorName) {
            $colors = array(
                'Grey ' => '',
                'Red' => '#cc0000',
                'Yellow' => '#FFC300',
                'Blue' => '#3E8FC1',
                'Violet' => '#C13E9F',
                'Purple' => '#900C3F',
                'Green' => '#86f488',
            );
            foreach ($colors as $name => $code) {
                $colorName = preg_replace('/^' . $name . '$/', $code, $colorName);
            }
            return $colorName;
        };

        foreach ($users as $user) {
            foreach ($worksheet->getRowIterator() as $row) {
                if ($row->getRowIndex() > 1) {

                    $name = $worksheet->getCell('A' . $row->getRowIndex())->getValue();
                    $code = $worksheet->getCell('B' . $row->getRowIndex())->getValue();
                    $price = $worksheet->getCell('C' . $row->getRowIndex())->getValue();
                    $duration = $worksheet->getCell('D' . $row->getRowIndex())->getValue();
                    $color = $worksheet->getCell('E' . $row->getRowIndex())->getValue();

                    $treatment = new Treatment();
                    $treatment->setName($name)
                        ->setCode($code)
                        ->setDuration((int)$duration)
                        ->setPrice($price)
                        ->setCalendarColour($replaceColor($color))
                        ->setOwner($user);

                    $manager->persist($treatment);

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
