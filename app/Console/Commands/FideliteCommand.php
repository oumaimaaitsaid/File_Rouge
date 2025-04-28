<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FideliteService;

class FideliteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fidelite:envoyer-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer des codes promo aux clients fidèles';

    /**
     * FideliteService instance.
     *
     * @var FideliteService
     */
    protected $fideliteService;

    /**
     * Create a new command instance.
     *
     * @param FideliteService $fideliteService
     * @return void
     */
    public function __construct(FideliteService $fideliteService)
    {
        parent::__construct();
        $this->fideliteService = $fideliteService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Début de l\'envoi des codes promo...');

        try {
            $this->fideliteService->verifierEtEnvoyerCodesPromo();
            $this->info('Envoi des codes promo terminé.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Erreur lors de l\'envoi des codes promo: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
