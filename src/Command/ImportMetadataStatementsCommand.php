<?php

declare(strict_types=1);

namespace Webauthn\Bundle\Command;

use Assert\Assertion;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webauthn\MetadataService\CanSupportImport;
use Webauthn\MetadataService\MetadataStatementRepository;
use Webauthn\MetadataService\Service\MetadataService;

class ImportMetadataStatementsCommand extends Command
{
    protected static $defaultName = 'metadata:statements:import-from-services';

    /**
     * @var MetadataService[]
     */
    private array $metadataServices;

    public function __construct(
        private MetadataStatementRepository $metadataStatementRepository,
        MetadataService ...$metadataServices
    ) {
        parent::__construct();
        $this->metadataServices = $metadataServices;
    }

    public function isEnabled(): bool
    {
        return $this->metadataStatementRepository instanceof CanSupportImport;
    }

    protected function configure(): void
    {
        $this
            ->setHelp(
                'This command will imports Metadata Statements from the Metadata Statement Services you declared.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Importing Metadata Statements');
        $io->progressStart();
        Assertion::isInstanceOf(
            $this->metadataStatementRepository,
            CanSupportImport::class,
            'The repository cannot import MDS'
        );
        foreach ($this->metadataServices as $metadataService) {
            $aaguids = $metadataService->list();
            foreach ($aaguids as $aaguid) {
                $mds = $metadataService->get($aaguid);
                $this->metadataStatementRepository->import($mds);
                $io->progressAdvance();
            }
        }
        $io->progressFinish();
        $io->title('Done!');

        return Command::SUCCESS;
    }
}