<?php

namespace Datatheke\Bundle\PagerBundle\Pager\Handler\Console;

use Datatheke\Bundle\PagerBundle\Pager\Filter;
use Datatheke\Bundle\PagerBundle\Pager\OrderBy;
use Datatheke\Bundle\PagerBundle\Pager\PagerInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultHandler implements ConsoleHandlerInterface
{
    protected $options;

    public function __construct(array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    protected function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'interactive' => true,
            )
        );
    }

    public function setInteractive($interactive)
    {
        $this->options['interactive'] = (bool) $interactive;
    }

    public function isInteractive()
    {
        return (bool) $this->options['interactive'];
    }

    public function handleConsole(PagerInterface $pager, OutputInterface $output, HelperSet $helperSet)
    {
        $interactive = $this->isInteractive();
        do {
            $this->createView($pager, $output, $helperSet);

            if ($interactive) {
                $interactive = $this->handleInput($pager, $output, $helperSet);
            }
        } while ($interactive);
    }

    public function handleInput(PagerInterface $pager, OutputInterface $output, HelperSet $helperSet)
    {
        $dialog = $helperSet->get('dialog');
        $formatter = $helperSet->get('formatter');

        $input = $dialog->ask($output, 'Page: ');
        switch ($input) {

            case 'q':
                return false;
                break;

            case 'n':
                $pager->setCurrentPageNumber($pager->getNextPageNumber());
                break;

            case 'p':
                $pager->setCurrentPageNumber($pager->getPreviousPageNumber());
                break;

            case 'i':
                $itemCountPerPage = $dialog->ask($output, 'Item per page: ');
                if (in_array($itemCountPerPage, $pager->getItemCountPerPageChoices())) {
                    $pager->setItemCountPerPage($itemCountPerPage);
                } else {
                    $output->writeln($formatter->formatBlock(
                        array('Item per page can be '.implode(', ', $pager->getItemCountPerPageChoices())),
                        'error'
                    ));
                }
                break;

            case 'o';
                $orderBy = $dialog->ask($output, 'Order by: ');
                if (null === $orderBy) {
                    $pager->setOrderBy(null);
                } else {
                    $orderBy = explode(' ', $orderBy);
                    $pager->setOrderBy(new OrderBy(array($orderBy[0] => $orderBy[1])));
                }
                break;

            case 'f':
                $filter = $dialog->ask($output, 'Filter: ');
                if (null === $filter) {
                    $pager->setFilter(null, 'handler');
                } else {
                    $filter = explode(' ', $filter);
                    if (!isset($filter[2])) {
                        $filter[2] = null;
                    }
                    $pager->setFilter(new Filter(array($filter[0]), array($filter[2]), array($filter[1])), 'handler');
                }
                break;

            default:
                $pager->setCurrentPageNumber($input);
                break;
        }

        return true;
    }

    protected function createView(PagerInterface $pager, OutputInterface $output, HelperSet $helperSet)
    {
        $table = $helperSet->get('table');
        $formatter = $helperSet->get('formatter');

        $output->writeln($formatter->formatBlock(
            array(
                'Page:     '.$pager->getCurrentPageNumber().' / '.$pager->getPageCount(),
                'Items:    '.$pager->getFirstItemNumber().'-'.$pager->getLastItemNumber().' / '.$pager->getTotalItemCount(),
            ),
            'bg=blue;fg=white'
        ));

        $table
            ->setRows($pager->getItems())
            ->render($output)
        ;
    }
}
