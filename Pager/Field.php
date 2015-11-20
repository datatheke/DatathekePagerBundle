<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class Field
{
    const TYPE_STRING = 'string';
    const TYPE_NUMBER = 'number';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_DATETIME = 'datetime';
    const TYPE_OBJECT = 'object';
    const TYPE_ARRAY = 'array';

    protected $propertyPath;
    protected $type;
    protected $qualifier;
    protected $metadata;

    protected $dataTransformers = array();

    public function __construct($propertyPath, $type = self::TYPE_STRING, $qualifier = null, array $metadata = array())
    {
        if (null === $qualifier) {
            $qualifier = $propertyPath;
        }

        $this->propertyPath = $propertyPath;
        $this->type = $type;
        $this->qualifier = $qualifier;
        $this->metadata = $metadata;
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

    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Deprecated, use getMetadata() instead.
     */
    public function getMetadatas()
    {
        return $this->metadata;
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
            } catch (TransformationFailedException $e) {
            }
        }

        return $value;
    }

    public function formatOutput($value)
    {
        foreach ($this->dataTransformers as $dataTransformer) {
            try {
                $value = $dataTransformer->transform($value);
            } catch (TransformationFailedException $e) {
            }
        }

        return $value;
    }
}
