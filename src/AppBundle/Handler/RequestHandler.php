<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 23.11.2016
 * Time: 16:22
 */

namespace AppBundle\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestHandler
{

    /**
     * Convert REST request to format applicable for form.
     *
     * @param FormInterface $form
     * @param Request $request
     */
    public function fixRequestAttributes(FormInterface $form, Request $request)
    {
        $formName = $form->getName();
        $data = array();

        if (!$request->get($formName)) {
            foreach ($request->request->keys() as $key) {
                $data[$key] = $request->request->all()[$key];
            }
            $request->request->set($form->getName(), $data);
        }

    }

}
