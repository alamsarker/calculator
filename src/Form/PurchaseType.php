<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\{
    AbstractType,
    FormBuilderInterface,
};
use Symfony\Component\Form\Extension\Core\Type\{
    MoneyType,
    IntegerType,
    HiddenType,
    SubmitType,
};
use App\Entity\Purchase;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Prepare the purchase form
 */
final class PurchaseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', IntegerType::class, [
                'required' => true,
            ])
            ->add('price', MoneyType::class, [
                'required' => true,
            ])
            ->add('Save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ]);
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
        ]);
    }
}
