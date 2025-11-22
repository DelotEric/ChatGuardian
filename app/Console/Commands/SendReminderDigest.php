<?php

namespace App\Console\Commands;

use App\Services\ReminderDigestService;
use Illuminate\Console\Command;

class SendReminderDigest extends Command
{
    protected $signature = 'reminders:digest {range=today : Période des rappels (today|week|overdue)}';

    protected $description = 'Envoyer le récapitulatif des rappels aux admins et bénévoles.';

    public function handle(ReminderDigestService $digestService): int
    {
        $range = $this->argument('range');

        if (!in_array($range, ['today', 'week', 'overdue'], true)) {
            $this->error('La période doit être today, week ou overdue.');

            return self::FAILURE;
        }

        [$reminderCount, $recipientCount] = $digestService->send($range);

        if ($reminderCount === 0) {
            $this->info('Aucun rappel à envoyer pour la période sélectionnée.');

            return self::SUCCESS;
        }

        if ($recipientCount === 0) {
            $this->warn('Aucun utilisateur admin/bénévole avec un email pour envoyer le récap.');

            return self::SUCCESS;
        }

        $this->info("Récapitulatif envoyé ({$reminderCount} rappels) à {$recipientCount} destinataire(s).");

        return self::SUCCESS;
    }
}
