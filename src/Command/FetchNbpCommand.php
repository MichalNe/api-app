<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\FetchService;
use App\Service\PrepareCurrencyService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fetch:nbp',
    description: 'fetch data from nbp and save in database',
)]
class FetchNbpCommand extends Command
{
    const FETCH_METHOD = 'GET';
    const FETCH_PATH = 'http://api.nbp.pl/api/exchangerates/tables/A/?format=json';

    /**
     * @param FetchService           $fs
     * @param PrepareCurrencyService $prepareCurrency
     * @param string|null            $name
     */
    public function __construct(
        private FetchService $fs,
        private PrepareCurrencyService $prepareCurrency,
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

        $saveResponse = $this->prepareCurrency->setData($response[0])->saveDataIntoDatabase();

        if (!$saveResponse) {
            $io->error('Something goes wrong with save to database');

            return Command::FAILURE;
        }

        $io->success('Saved currencies');

        return Command::SUCCESS;
    }
}
