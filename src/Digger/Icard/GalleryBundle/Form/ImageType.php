<?php

namespace Digger\Icard\GalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

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
            ))
            ->add('note', null, array(
                'required' => false,
                "attr" => array(
                    "rows" => 5,
                    'style' => 'width:360px;'
                )
            ))
            ->add('enabled', 'checkbox', array(
                'required' => false,
                'label_render' => false
            ))
            ->add('file', 'file', array('required' => false))
            ->add('submit','submit',  array(
                'attr' => array(
                    'class' => 'btn btn-success',
                    'icon'       => 'save',
                    'icon_inverted' => true,
                    'style' => 'margin-top:10px;'
                )
            ))
//            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
//                $form  = $event->getForm();
//                $image = $event->getData();
//                $form->get('title')->setData($image->getTitle());
//            });
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
