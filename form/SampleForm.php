<?php

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints;

class SampleForm extends AbstractType
{
  public $title;
  public $family_name;
  public $email;

  public static $choices = array('Mr.'=>'Mr.', 'Miss'=>'Miss', 'Mrs.'=>'Mrs.');

  static public function loadValidatorMetadata(ClassMetadata $metadata)
  {
    //title
    $metadata->addPropertyConstraint('title', new Constraints\NotBlank());
    $metadata->addPropertyConstraint('title', new Constraints\Choice(array('choices'=>array_keys(self::$choices))));
    //name
    $metadata->addPropertyConstraint('family_name', new Constraints\NotNull());
    $metadata->addPropertyConstraint('family_name', new Constraints\NotBlank());
    //email
    $metadata->addPropertyConstraint('email', new Constraints\Email());
  }

  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('title', 'choice', array('choices'=>self::$choices));
    $builder->add('family_name', 'text');
    $builder->add('email', 'text');
  }
  
  public function getName()
  {
    return 'sample';
  }
}