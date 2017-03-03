<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.11.2016
 * Time: 16:01
 */

namespace AppBundle\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormHandler
{

    /**
     * Process form and returns true on success

     * @param FormInterface $form
     * @param $data
     * @param Request $request
     * @return mixed|null
     */
    public function processForm(FormInterface $form, $data, Request $request)
    {
        $form->setData($data);

        if (in_array($request->getMethod(), array('POST', 'PUT'))) {

            $form->submit($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entity = $form->getData();

                return $entity;
            }
        }

        return null;
    }

}
