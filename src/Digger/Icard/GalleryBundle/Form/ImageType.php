<?php

namespace Digger\Icard\GalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'data' => 'Default title'
            ))
            ->add('note', null, array(
                'required' => false,
                'data' => 'Totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia cor magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? '
            ))
            ->add('enabled', 'checkbox', array(
                'required' => false,
                'label_render' => false,
                'data' => true
            ))
            ->add('file', 'file')
            ->add('submit','submit',  array(
                'attr' => array(
                    'class' => 'btn btn-success'
                )
             ))
           ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Digger\Icard\GalleryBundle\Entity\Image'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'digger_icard_gallerybundle_image';
    }
}
