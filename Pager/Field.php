<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class Field
{
    const TYPE_STRING   = 'string';
    const TYPE_NUMBER   = 'number';
    const TYPE_DATETIME = 'datetime';

    protected $propertyPath;
    protected $type;
    protected $qualifier;
    protected $metadatas;

    protected $dataTransformers = array();

    public function __construct($propertyPath, $type = self::TYPE_STRING, $qualifier = null, array $metadatas = array())
    {
        if (null === $qualifier) {
            $qualifier = $propertyPath;
        }

        $this->propertyPath = $propertyPath;
        $this->type         = $type;
        $this->qualifier    = $qualifier;
        $this->metadatas    = $metadatas;
    }

    public function getPropertyPath()
    {
        return $this->propertyPath;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getQualifier()
    {
        return $this->qualifier;
    }

    public function getMetadatas()
    {
        return $this->metadatas;
    }

    public function addDataTransformer(DataTransformerInterface $dataTransformer)
    {
        $this->dataTransformers[] = $dataTransformer;
    }

    public function formatInput($value)
    {
        foreach ($this->dataTransformers as $dataTransformer) {
            try {
                $value = $dataTransformer->reverseTransform($value);
            }
            catch (TransformationFailedException $e) {
            }
        }

        return $value;
    }

    public function formatOutput($value)
    {
        foreach ($this->dataTransformers as $dataTransformer) {
            try {
                $value = $dataTransformer->transform($value);
            }
            catch (TransformationFailedException $e) {
            }
        }

        return $value;
    }
}
