<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 16/03/2017
 * Time: 18:47
 */

namespace MicroCMS\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', TextareaType::class);
    }

    public function getName()
    {
        return 'comment';
    }
}
