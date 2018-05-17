<?php
/**
 * Created by PhpStorm.
 * User: Stepan Yudin <stepan.sib@gmail.com>
 * Date: 05.08.2017
 * Time: 22:04
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Goal;
use AppBundle\Form\Traits\AddFieldOptionsTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;

/**
 * Class GoalType
 */
class GoalType extends AbstractType
{

    use AddFieldOptionsTrait;

    /** @var  Translator */
    protected $translator;

    /**
     * GoalType constructor.
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'goal',
            TextType::class,
            [
                'label' => 'app.goal.goal',
                'required' => true,
            ]
        )->add(
            'actionStep',
            TextareaType::class,
            [
                'label' => 'app.goal.action_step',
                'required' => true,
            ]
        )->add(
            'when',
            ChoiceType::class,
            [
                'label' => 'app.goal.when',
                'required' => true,
                'choices' => [
                    Goal::WHEN_MONTH => $this->translator->trans('app.goal.when_types.month'),
                    Goal::WHEN_QUARTER => $this->translator->trans('app.goal.when_types.quarter'),
                    Goal::WHEN_YEAR => $this->translator->trans('app.goal.when_types.year'),
                ]
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Goal',
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'app_goal';
    }

}
