<?php

namespace Datatheke\Bundle\PagerBundle\DataGrid;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;

use Datatheke\Bundle\PagerBundle\DataGrid\Handler\Console\ConsoleHandlerInterface;
use Datatheke\Bundle\PagerBundle\Pager\ConsolePager;

class ConsoleDataGrid extends DataGrid implements ConsoleDataGridInterface
{
    protected $handler;

    public function __construct(ConsolePager $pager, ConsoleHandlerInterface $handler, array $columns = null, array $options = array())
    {
        $this->handler = $handler;

        parent::__construct($pager, $columns, $options);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'interactive' => true
            )
        );
    }

    public function handleConsole(OutputInterface $output, HelperSet $helperSet)
    {
        $this->initialize();

        return $this->handler->handleConsole($this, $output, $helperSet);
    }
}
