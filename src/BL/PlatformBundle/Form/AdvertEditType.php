<?php

namespace BL\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AdvertEditType extends AbstractType
{
    public function getParent()
    {
        return AdvertType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('date');


        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function(FormEvent $event) {
                $advert = $event->getData();

                if(null === $advert) {
                    return;
                }
            }
        );

    }
}
