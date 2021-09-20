<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\FetchService;
use App\Service\PrepareHeaderService;
use App\Service\PrepareSettingService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fetch:optAd360',
    description: 'fetch data from optAd360 and save in database',
)]
class FetchOptAd360Command extends Command
{
    const FETCH_METHOD = 'GET';
    const FETCH_PATH = 'https://api.optad360.com/get?%20key=HJGHcZvJHZhjgew6qe67q6GHcZv3fdsAqxbvB33fdV&startDate=2021-08-%2011&endDate=2021-08-11&output=json';

    /**
     * @param FetchService          $fs
     * @param PrepareHeaderService  $prepareHeader
     * @param PrepareSettingService $prepareSetting
     * @param string|null           $name
     */
    public function __construct(
        private FetchService $fs,
        private PrepareHeaderService $prepareHeader,
        private PrepareSettingService $prepareSetting,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $counter = 0;

        $io = new SymfonyStyle($input, $output);

        $response = $this->fs->fetchData(self::FETCH_METHOD, self::FETCH_PATH);

        if (!$response) {
            $io->error('Something goes wrong with fetch from api');

            return Command::FAILURE;
        }

        if (isset($response['settings'])) {
            $settingAction = $this->prepareSetting->setData($response['settings'])->saveDataIntoDatabase();

            if (!$settingAction) {
                $io->error('Something goes wrong with saving setting, Make sure that the currency has been entered.');

                return Command::FAILURE;
            }

            $io->success('Saved settings');

            $counter++;
        }

        if (isset($response['headers'])) {
            $settingAction = $this->prepareHeader->setData($response['headers'])->saveDataIntoDatabase();

            if (!$settingAction) {
                $io->error('Something goes wrong with saving headers');

                return Command::FAILURE;
            }

            $io->success('Saved headers');

            $counter++;
        }

        if ($counter === 0) {
            $io->info('Nothing to do');
        }

        return Command::SUCCESS;
    }
}
