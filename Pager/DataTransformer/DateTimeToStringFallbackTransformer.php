<?php

namespace Datatheke\Bundle\PagerBundle\Pager\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class DateTimeToStringFallbackTransformer implements DataTransformerInterface
{
    public function transform($dateTime)
    {
        return $dateTime;
    }

    public function reverseTransform($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        try {
            $datetime = new \DateTime($value);
        }
        catch (\Exception $e) {
            return $value;
        }

        return $datetime;
    }
}
