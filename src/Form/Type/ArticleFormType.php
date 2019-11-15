<?php

namespace App\Form\Type;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Título',
                'help' => 'El campo no puede superar bla bla bla'
            ])
            ->add('body', TextareaType::class, [
                'label' => 'Cuerpo del artículo'
            ])
            ->add('category', EntityType::class, [
                'label' => 'Categoría',
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('state', ChoiceType::class, [
                'choices' => [
                    'value a' => 1,
                    'value b' => 2
                ],
                'mapped' => false
            ])
            ->add('isPublished', CheckboxType::class, [
                'label' => '¿Publicado?',
                'required' => false
            ])
            ->add('terms', CheckboxType::class, [
                'label' => 'Acepto los términos y condiciones',
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Guardar'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {  
        $resolver->setDefaults([
            'data_class' => Article::class
        ]);
    }
}