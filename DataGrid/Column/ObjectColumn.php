<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column;

use Doctrine\Common\Persistence\ObjectRepository;

use Datatheke\Bundle\PagerBundle\Pager\DataTransformer\ObjectToStringTransformer;
use Datatheke\Bundle\PagerBundle\Pager\Field;

class ObjectColumn extends AbstractColumn
{
    protected $property;
    protected $objectRepository;

    public function __construct(Field $field = null, $label = null, $property = null, $objectRepository = null)
    {
        parent::__construct($field, $label);

        $this->property         = $property;
        $this->objectRepository = $objectRepository;
    }

    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }

    public function setObjectRepository(ObjectRepository $objectRepository)
    {
        $this->objectRepository = $objectRepository;

        return $this;
    }

    public function initialize()
    {
        if (null !== $this->property) {
            $this->field->addDataTransformer(
                new ObjectToStringTransformer($this->property, $this->objectRepository)
            );
        }
    }

    public function format($value)
    {
        return $this->field->formatOutput($value);
    }

    public function getType()
    {
        return 'object';
    }
}
