<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column;

use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;

class LocalizedNumberColumn extends AbstractColumn
{
    protected $precision;
    protected $grouping     = true;
    protected $roundingMode;

    public function setPrecision($precision)
    {
        $this->precision = $precision;

        return $this;
    }

    public function setGrouping($grouping)
    {
        $this->grouping = $grouping;

        return $this;
    }

    public function setRoundingMode($roundingMode)
    {
        $this->roundingMode = $roundingMode;

        return $this;
    }

    public function initialize()
    {
        $this->field->addDataTransformer(
            new NumberToLocalizedStringTransformer($this->precision, $this->grouping, $this->roundingMode)
        );
    }

    public function getType()
    {
        return 'localized_number';
    }
}
