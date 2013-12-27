<?php

namespace Datatheke\Bundle\PagerBundle\Pager\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

use Doctrine\Common\Persistence\ObjectRepository;

class ObjectToStringTransformer implements DataTransformerInterface
{
    protected $accessor;
    protected $property;
    protected $objectRepository;

    public function __construct($property, ObjectRepository $objectRepository = null)
    {
        $this->accessor         = PropertyAccess::createPropertyAccessor();
        $this->property         = $property;
        $this->objectRepository = $objectRepository;
    }

    public function transform($item)
    {
        if (!is_object($item)) {
            return;
        }

        return $this->accessor->getValue($item, $this->property);
    }

    public function reverseTransform($value)
    {
        if (!$this->objectRepository) {
            return $value;
        }

        try {
            $item = $this->objectRepository->findOneBy(array($this->property => $value));
        } catch (\Exception $e) {
            return $value;
        }

        if (null === $item) {
            return $value;
        }

        return $item;
    }
}
