<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column;

use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer;

class BooleanColumn extends AbstractColumn
{
    protected $trueValue;

    public function setTrueValue($trueValue)
    {
        $this->trueValue = $trueValue;

        return $this;
    }

    public function getTrueValue()
    {
        return $this->trueValue;
    }

    public function initialize()
    {
        if (null !== $this->trueValue) {
            $this->field->addDataTransformer(
                new BooleanToStringTransformer($this->trueValue)
            );
        }
    }

    public function getType()
    {
        return 'boolean';
    }
}
