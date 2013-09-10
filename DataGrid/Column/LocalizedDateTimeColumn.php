<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid\Column;

use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToLocalizedStringTransformer;

use Datatheke\Bundle\PagerBundle\Pager\DataTransformer\DateTimeToStringFallbackTransformer;

class LocalizedDateTimeColumn extends AbstractColumn
{
    protected $inputTimezone;
    protected $outputTimezone;
    protected $dateFormat;
    protected $timeFormat;
    protected $calendar;
    protected $format;

    public function setInputTimezone($inputTimezone)
    {
        $this->inputTimezone = $inputTimezone;

        return $this;
    }

    public function setOutputTimezone($outputTimezone)
    {
        $this->outputTimezone = $outputTimezone;

        return $this;
    }

    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;

        return $this;
    }

    public function setTimeFormat($timeFormat)
    {
        $this->timeFormat = $timeFormat;

        return $this;
    }

    public function setCalendar($calendar)
    {
        $this->calendar = $calendar;

        return $this;
    }

    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    public function initialize()
    {
        if (null === $this->calendar) {
            $this->calendar = \IntlDateFormatter::GREGORIAN;
        }

        $this->field->addDataTransformer(
            new DateTimeToLocalizedStringTransformer(
                $this->inputTimezone,
                $this->outputTimezone,
                $this->dateFormat,
                $this->timeFormat,
                $this->calendar,
                $this->format
                )
        );

        $this->field->addDataTransformer(
            new DateTimeToStringFallbackTransformer()
        );
    }

    public function getType()
    {
        return 'localized_datetime';
    }
}
