<?php

namespace App\Command;

use App\Service\FetchService;
use App\Service\PrepareUserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fetch:fakeapi',
    description: 'fetch data from FakeApi and save in database',
)]
class FetchFakeapiCommand extends Command
{
    const FETCH_METHOD = 'GET';
    const FETCH_PATH = 'https://randomuser.me/api/?results=20&format=json';

    /**
     * @param FetchService       $fs
     * @param PrepareUserService $prepareUser
     * @param string|null        $name
     */
    public function __construct(
        private FetchService $fs,
        private PrepareUserService $prepareUser,
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

        $saveResponse = $this->prepareUser->setData($response)->saveDataIntoDatabase();

        if (!$saveResponse) {
            $io->error('Something goes wrong with save to database');

            return Command::FAILURE;
        }

        $io->success('Saved users with addresses');

        return Command::SUCCESS;
    }
}
