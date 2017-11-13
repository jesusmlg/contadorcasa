<?php

namespace FacturasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;


class FacturaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lectura',TextType::class, array('attr' => array ('class' => 'form-control')))
                ->add('importe',MoneyType::class, array('attr' => array ('class' => 'form-control')))
                ->add('fecha',DateTimeType::class,array(
                    'data' => new \DateTime("now"),
                    'date_format' => 'dd-MM-yyyy', 'attr' => array('class' => 'form-control input-inline datepicker'))
                     )
                ->add('Agregar', SubmitType::class, array('attr' => array('class' => 'btn btn-primary')));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FacturasBundle\Entity\Factura'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'facturasbundle_factura';
    }


}
