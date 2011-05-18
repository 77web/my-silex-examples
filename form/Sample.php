<?php


use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints;

class Sample
{
  public $title;
  public $name;
  public $email;
  
  public static $choices = array('Mr.'=>'Mr.', 'Miss'=>'Miss', 'Mrs.'=>'Mrs.');
  
  static public function loadValidatorMetadata(ClassMetadata $metadata)
  {
    //title
    $metadata->addPropertyConstraint('title', new Constraints\NotBlank());
    $metadata->addPropertyConstraint('title', new Constraints\Choice(array('choices'=>array_keys(self::$choices))));
    //name
    $metadata->addPropertyConstraint('name', new Constraints\NotNull());
    $metadata->addPropertyConstraint('name', new Constraints\NotBlank());
    //email
    $metadata->addPropertyConstraint('email', new Constraints\Email());
  }



}