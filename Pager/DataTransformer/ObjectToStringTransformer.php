<?php

namespace Datatheke\Bundle\PagerBundle\Pager\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\Common\Persistence\ObjectRepository;

class ObjectToStringTransformer implements DataTransformerInterface
{
    const FALLBACK_PROPERTY = 'id';

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

        if (null !== $this->property) {
            return $this->accessor->getValue($item, $this->property);
        } elseif (method_exists($item, '__toString')) {
            return (string) $item;
        }

        return $this->accessor->getValue($item, self::FALLBACK_PROPERTY);
    }

    public function reverseTransform($value)
    {
        if (null === $this->objectRepository) {
            return $value;
        }

        try {
            $property = null !== $this->property ? $this->property : self::FALLBACK_PROPERTY;
            $item = $this->objectRepository->findOneBy(array($property => $value));
        } catch (\Exception $e) {
            return $value;
        }

        if (null === $item) {
            return $value;
        }

        return $item;
    }
}
