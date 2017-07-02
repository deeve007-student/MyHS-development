<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 03.07.2017
 * Time: 2:05
 */

namespace AppBundle\Twig;

use AppBundle\Entity\MessageLog;

class MessageLogExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('app_message_log_type', array($this, 'messageLogTypeFilter')),
        );
    }

    public function messageLogTypeFilter($type)
    {
        $r = new \ReflectionClass(MessageLog::class);
        return $r->getConstant('TYPE_' . mb_strtoupper($type) . '_ICON');
    }
}
