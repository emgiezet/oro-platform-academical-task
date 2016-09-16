<?php

namespace App\IssueBundle\Form\Type;

use App\IssueBundle\Entity\Issue;
use Oro\Bundle\FormBundle\Form\Type\OroRichTextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary')
            ->add('description', OroRichTextType::NAME)
            ->add('type', 'choice', ['choices' => [Issue::$typeArray]])
            ->add('priority')
            ->add('resolution')
            ->add('asignee')
            ->add('relatedIssues')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\IssueBundle\Entity\Issue',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_issuebundle_issue';
    }
}
