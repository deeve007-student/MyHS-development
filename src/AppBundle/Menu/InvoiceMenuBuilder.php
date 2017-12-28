<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 15.05.2017
 * Time: 13:39
 */

namespace AppBundle\Menu;

use AppBundle\Entity\Invoice;
use AppBundle\Utils\Hasher;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\Translator;

class InvoiceMenuBuilder
{
    /** @var FactoryInterface */
    protected $factory;

    /** @var RequestStack */
    protected $requestStack;

    /** @var Hasher */
    protected $hasher;

    /** @var Router */
    protected $router;

    /** @var EntityManager */
    protected $entityManager;

    /** @var Translator */
    protected $translator;

    protected static $mobile = false;

    public function __construct(
        EntityManager $entityManager,
        FactoryInterface $factory,
        RequestStack $requestStack,
        Router $router,
        Translator $translator,
        Hasher $hasher
    )
    {
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->requestStack = $requestStack;
        $this->router = $router;
        $this->translator = $translator;
        $this->hasher = $hasher;
    }

    protected function getInvoiceHash()
    {
        if ($invoiceId = $this->requestStack->getCurrentRequest()->get('id')) {
            if ($invoiceId instanceof Invoice) {
                return $this->hasher->encodeObject($invoiceId);
            } else {
                return $invoiceId;
            }
        }

        return null;
    }

    protected function getInvoiceId()
    {
        if ($invoiceHash = $this->getInvoiceHash()) {
            return $this->hasher->decode($invoiceHash, Invoice::class);
        }

        return null;
    }

    /**
     * @return \AppBundle\Entity\Invoice|null|object
     */
    protected function getInvoice()
    {
        return $this->entityManager->getRepository('AppBundle:Invoice')->find($this->getInvoiceId());
    }

    public function createMenu(array $options)
    {

        $menu = $this->factory->createItem('invoice');
        //$menu->setChildrenAttribute('class', 'menu-sub');

        foreach ($this->getInvoice()->getAvailableStatuses() as $availableStatus) {
            $menu->addChild(
                $this->translator->trans('app.invoice.change_status', ['%status%' => $this->translator->trans(Invoice::getStatusLabel($availableStatus))]),
                array(
                    'route' => 'invoice_status_update',
                    'linkAttributes' => array(
                        'class' => !static::$mobile ?
                            'btn btn-' . Invoice::getColorClass($availableStatus) . ' btn-block' :
                            '',
                    ),
                    'routeParameters' => array(
                        'id' => $this->getInvoiceHash(),
                        'status' => $availableStatus,
                    ),
                )
            );
        }

        $menu->addChild(
            'app.action.duplicate',
            array(
                'route' => 'invoice_duplicate',
                'linkAttributes' => array(
                    'class' => !static::$mobile ?
                        'btn btn-default btn-block' :
                        '',
                ),
                'routeParameters' => array(
                    'id' => $this->getInvoiceHash(),
                ),
            )
        );

        if ($this->getInvoice() && $this->getInvoice()->getAmountDue() > 0) {
            $menu->addChild(
                'app.invoice_payment.add',
                array(
                    'route' => 'invoice_payment_create',
                    'linkAttributes' => array(
                        'class' => !static::$mobile ?
                            'btn btn-success btn-block app-invoice-payment-create' :
                            'app-invoice-payment-create',
                    ),
                    'routeParameters' => array(
                        'id' => $this->getInvoiceHash(),
                    ),
                )
            );
        }

        if ($this->getInvoice()->getStatus() !== Invoice::STATUS_DRAFT) {

            $menu->addChild(
                'app.invoice.pdf',
                array(
                    'route' => 'invoice_pdf',
                    'linkAttributes' => array(
                        'class' => !static::$mobile ?
                            'btn btn-default btn-block' :
                            '',
                        'target' => '_blank',
                    ),
                    'routeParameters' => array(
                        'id' => $this->getInvoiceHash(),
                    ),
                )
            );

            if ($this->getInvoice()->getPatient()) {
                $menu->addChild(
                    'app.invoice.email_pdf',
                    array(
                        'route' => 'invoice_pdf',
                        'linkAttributes' => array(
                            'data-id' => $this->getInvoiceHash(),
                            'class' => !static::$mobile ?
                                'btn btn-default btn-block send-invoice-pdf' :
                                'send-invoice-pdf',
                        ),
                        'routeParameters' => array(
                            'id' => $this->getInvoiceHash(),
                        ),
                    )
                );
            }

        }

        $menu->addChild(
            'app.invoice.delete',
            array(
                'uri' => '#',
                'linkAttributes' => array(
                    'data-href' => $this->router->generate('invoice_delete', ['id' => $this->getInvoiceHash()]),
                    'class' => !static::$mobile ?
                        'btn btn-danger btn-block' :
                        '',
                    'data-toggle' => 'delete-confirmation',
                    'data-entity-label' => 'app.invoice.label',
                ),
            )
        );

        return $menu;
    }
}
