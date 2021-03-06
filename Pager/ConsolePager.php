<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\AdapterInterface;
use Datatheke\Bundle\PagerBundle\Pager\Handler\Console\ConsoleHandlerInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsolePager extends Pager implements ConsolePagerInterface
{
    protected $handler;

    public function __construct(AdapterInterface $adapter, ConsoleHandlerInterface $handler, array $options = array())
    {
        $this->handler = $handler;

        parent::__construct($adapter, $options);
    }

    protected function setDefaultOptions(OptionsResolver $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'interactive' => true,
            )
        );
    }

    public function handleConsole(OutputInterface $output, HelperSet $helperSet)
    {
        return $this->handler->handleConsole($this, $output, $helperSet);
    }

    public function getHandler()
    {
        return $this->handler;
    }
}
