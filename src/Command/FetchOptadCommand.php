<?php

namespace App\Command;

use App\Service\FetchService;
use App\Service\PrepareCurrencyService;
use App\Service\PrepareOptadService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fetch:optad',
    description: 'Add a short description for your command',
)]
class FetchOptadCommand extends Command
{
    const FETCH_METHOD = 'GET';
    const FETCH_PATH = 'https://api.optad360.com/get?key=HJGHcZvJHZhjgew6qe67q6GHcZv3fdsAqxbvB33fdV&startDate=2021-08-11&endDate=2021-08-11&output=json';

    /**
     * @param FetchService           $fs
     * @param PrepareOptadService    $prepareOptad
     * @param string|null            $name
     */
    public function __construct(
        private FetchService $fs,
        private PrepareOptadService $prepareOptad,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $response = $this->fs->fetchData(self::FETCH_METHOD, self::FETCH_PATH);

        if (!$response) {
            $io->error('Something goes wrong with fetch from api');

            return Command::FAILURE;
        }

        $saveResponse = $this->prepareOptad->setData($response)->saveDataIntoDatabase();

        if (!$saveResponse) {
            $io->error('Something goes wrong with save to database');

            return Command::FAILURE;
        }

        $io->success('Saved optad');

        return Command::SUCCESS;
    }
}
