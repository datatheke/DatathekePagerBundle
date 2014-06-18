<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column;

class StringColumn extends AbstractColumn
{
    protected $maxLength;
    protected $moreText = '...';

    public function setMaxLength($maxLength)
    {
        $this->maxLength = (int) $maxLength;

        return $this;
    }

    public function getMaxLength()
    {
        return $this->maxLength;
    }

    public function setMoreText($moreText)
    {
        $this->moreText = $moreText;

        return $this;
    }

    public function getMoreText()
    {
        return $this->moreText;
    }

    public function format($value)
    {
        $value = $this->field->formatOutput($value);
        if (null !== $this->maxLength && strlen($value) > $this->maxLength) {
            $value = substr($value, 0, $this->maxLength).$this->moreText;
        }

        return $value;
    }

    public function getType()
    {
        return 'string';
    }
}
