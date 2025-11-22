<?php

namespace App\Console\Commands;

use App\Services\StockAlertService;
use Illuminate\Console\Command;

class SendStockAlert extends Command
{
    protected $signature = 'stocks:alert';

    protected $description = 'Envoyer une alerte email pour les stocks faibles';

    public function handle(StockAlertService $service): int
    {
        [$itemsCount, $sentCount] = $service->send();

        if ($itemsCount === 0) {
            $this->info('Aucun article sous le seuil, aucun email envoyé.');
            return self::SUCCESS;
        }

        if ($sentCount === 0) {
            $this->warn("{$itemsCount} articles sont sous le seuil mais aucun destinataire n'a été configuré.");
            return self::SUCCESS;
        }

        $this->info("Alerte envoyée à {$sentCount} destinataire(s) pour {$itemsCount} article(s) faible(s).");

        return self::SUCCESS;
    }
}
