<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Product Name'
            ))
            ->add('price', NumberType::class, array(
                'label' => 'Product Price'
            ))
            ->add('quantity', NumberType::class, array(
                'label' => 'Product Quantity'
            ))
            ->add('description', TextType::class, array(
                'label' => 'Product Decription'
            ))
            ->add('image', TextType::class, array(
                'label' => 'Product Image'
            ))
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'name',
            
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('save', SubmitType::class, array(
                'label' => 'Save',
                'attr' => array(
                    'class' => 'fv-btn'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
