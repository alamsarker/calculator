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
use App\Entity\Sale;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Validator\Stock as AvailStock;

/**
 * Prepare the sale form
 */
final class SaleType extends AbstractType
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
                'constraints' => [
                    new NotBlank(),
                    new AvailStock()
                ],
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
            'data_class' => Sale::class,
        ]);
    }
}
