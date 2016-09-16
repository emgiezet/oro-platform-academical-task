<?php

namespace App\IssueBundle\Form\Type;

use App\IssueBundle\Entity\Issue;
use Doctrine\ORM\EntityRepository;
use Oro\Bundle\FormBundle\Form\Type\OroRichTextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class IssueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $issue = $builder->getData();

        $builder
            ->add('summary', 'text', ['constraints' => [
                new NotBlank(),
                new Length(['min' => 5, 'max' => '255']),
            ]])
            ->add('description', OroRichTextType::NAME)
            ->add('type', 'choice', ['choices' => [Issue::$typeArray]])
            ->add('priority', 'entity',
                [
                    'class' => 'App\IssueBundle\Entity\Priority',
                    'required' => true,
                    'label' => 'app.issue.priority.label',
                    'constraints' => [
                        new NotNull(),
                    ],
                ])
            ->add('resolution')
            ->add('asignee', 'oro_user_organization_acl_select')
            ->add('relatedIssues', 'entity',
                [
                    'class' => 'App\IssueBundle\Entity\Issue',
                    'label' => 'app.issue.relatedIssues.label',
                    'multiple' => true,
                    'query_builder' => function (EntityRepository $er) use ($issue) {
                        $parameters = [
                            'deleted' => false,
                        ];

                        $qb = $er->createQueryBuilder('i')
                            ->andWhere('i.deleted = :deleted')
                            ->orderBy('i.summary', 'ASC');

                        if ($issue instanceof Issue && $issue->getId() > 0) {
                            $qb->andWhere('i.id <> :selfId');

                            $parameters['selfId'] = $issue->getId();
                        }

                        $qb->setParameters($parameters);

                        return $qb;
                    },
                ])
            ->add(
                'parent',
                'entity',
                [
                    'class' => 'App\IssueBundle\Entity\Issue',
                    'label' => 'app.issue.parent.label',
                    'placeholder' => '',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('i')
                            ->where('i.type = :type')
                            ->andWhere('i.deleted = :deleted')
                            ->orderBy('i.summary', 'ASC')
                            ->setParameters([
                                'type' => Issue::TYPE_STORY,
                                'deleted' => false,
                            ]);
                    },
                ]
            )
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
