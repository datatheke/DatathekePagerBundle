<?php

namespace Datatheke\Bundle\PagerBundle\Pager;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Datatheke\Bundle\PagerBundle\Pager\Adapter\AdapterInterface;

class ConsolePager extends Pager implements ConsolePagerInterface
{
    protected $options;

    public function __construct(AdapterInterface $adapter, array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);
        $this->options = $resolver->resolve($options);

        parent::__construct($adapter, $this->options['item_count_per_page'], $this->options['item_count_per_page_choices']);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'item_count_per_page',
            )
        );

        $resolver->setDefaults(array(
            'item_count_per_page_choices' => array(),
            'interactive'                 => true,
            )
        );
    }

    public function handleConsole(OutputInterface $output, HelperSet $helperSet)
    {
        $interactive = $this->options['interactive'];
        do {
            $this->renderPager($output, $helperSet);

            if ($interactive) {
                $interactive = $this->handleInput($output, $helperSet);
            }

        } while ($interactive);
    }

    protected function renderPager(OutputInterface $output, HelperSet $helperSet)
    {
        $table     = $helperSet->get('table');
        $formatter = $helperSet->get('formatter');

        $output->writeln($formatter->formatBlock(
            array(
                'Page:     '.$this->getCurrentPageNumber().' / '.$this->getPageCount(),
                'Items:    '.$this->getFirstItemNumber().'-'.$this->getLastItemNumber().' / '.$this->getTotalItemCount()
            ),
            'bg=blue;fg=white'
        ));

        $table
            ->setRows($this->getItems())
            ->render($output)
        ;
    }

    public function handleInput(OutputInterface $output, HelperSet $helperSet)
    {
        $dialog    = $helperSet->get('dialog');
        $formatter = $helperSet->get('formatter');

        $input = $dialog->ask($output, 'Page: ');
        switch ($input) {

            case 'q':
                return false;
                break;

            case 'n':
                $this->setCurrentPageNumber($this->getNextPageNumber());
                break;

            case 'p':
                $this->setCurrentPageNumber($this->getPreviousPageNumber());
                break;

            case 'i':
                $itemCountPerPage = $dialog->ask($output, 'Item per page: ');
                if (in_array($itemCountPerPage, $this->options['item_count_per_page_choices'])) {
                    $this->setItemCountPerPage($itemCountPerPage);
                }
                else {
                    $output->writeln($formatter->formatBlock(
                        array('Item per page can be '.implode(', ', $this->options['item_count_per_page_choices'])),
                        'error'
                    ));
                }
                break;

            case 'o';
                $orderBy = $dialog->ask($output, 'Order by: ');
                if (null === $orderBy) {
                    $this->setOrderBy(null);
                }
                else {
                    $orderBy = explode(' ', $orderBy);
                    $this->setOrderBy(new OrderBy(array($orderBy[0] => $orderBy[1])));
                }
                break;

            case 'f':
                $filter = $dialog->ask($output, 'Filter: ');
                if (null === $filter) {
                    $this->setFilter(null);
                }
                else {
                    $filter = explode(' ', $filter);
                    if (!isset($filter[2])) {
                        $filter[2] = null;
                    }
                    $this->setFilter(new Filter(array($filter[0]), array($filter[2]), array($filter[1])));
                }
                break;

            default:
                $this->setCurrentPageNumber($input);
                break;
        }

        return true;
    }
}