<?php

namespace App\Console\Commands;

use App\Mail\MedicalReminder;
use App\Models\MedicalCare;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendMedicalReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medical:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie des rappels automatiques pour les soins médicaux à venir';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏥 Vérification des soins médicaux nécessitant un rappel...');

        $remindersSent = 0;

        // 1. Soins en retard (URGENT)
        $overdueCares = MedicalCare::where('status', 'scheduled')
            ->where('care_date', '<', now())
            ->get();

        foreach ($overdueCares as $care) {
            $this->sendReminder($care, -1, 'overdue');
            $remindersSent++;
        }

        // 2. Soins prévus aujourd'hui
        $todayCares = MedicalCare::where('status', 'scheduled')
            ->whereDate('care_date', now())
            ->get();

        foreach ($todayCares as $care) {
            $this->sendReminder($care, 0, 'today');
            $remindersSent++;
        }

        // 3. Soins dans 3 jours
        $threeDaysCares = MedicalCare::where('status', 'scheduled')
            ->whereDate('care_date', now()->addDays(3))
            ->where(function ($query) {
                $query->where('reminder_count', 0)
                    ->orWhereNull('reminder_count');
            })
            ->get();

        foreach ($threeDaysCares as $care) {
            $this->sendReminder($care, 3, 'soon');
            $remindersSent++;
        }

        // 4. Soins dans 7 jours
        $sevenDaysCares = MedicalCare::where('status', 'scheduled')
            ->whereDate('care_date', now()->addDays(7))
            ->where(function ($query) {
                $query->where('reminder_count', 0)
                    ->orWhereNull('reminder_count');
            })
            ->get();

        foreach ($sevenDaysCares as $care) {
            $this->sendReminder($care, 7, 'soon');
            $remindersSent++;
        }

        $this->info("✅ {$remindersSent} rappel(s) envoyé(s) avec succès.");

        return Command::SUCCESS;
    }

    /**
     * Envoie un rappel pour un soin médical
     */
    private function sendReminder(MedicalCare $care, int $daysUntilDue, string $urgencyLevel): void
    {
        $care->load(['cat', 'partner', 'responsible']);

        // Déterminer le destinataire
        $recipientEmail = $this->getRecipientEmail($care);

        if (!$recipientEmail) {
            $this->warn("⚠️  Aucun email trouvé pour le soin #{$care->id} ({$care->cat->name})");
            return;
        }

        try {
            // Envoyer l'email
            Mail::to($recipientEmail)->send(
                new MedicalReminder($care, $daysUntilDue, $urgencyLevel)
            );

            // Mettre à jour le suivi des rappels
            $care->update([
                'reminder_sent_at' => now(),
                'reminder_count' => ($care->reminder_count ?? 0) + 1,
            ]);

            $urgencyIcon = match ($urgencyLevel) {
                'overdue' => '🚨',
                'today' => '⚠️',
                default => '📅',
            };

            $this->line("{$urgencyIcon} Rappel envoyé: {$care->cat->name} - {$care->title} → {$recipientEmail}");

        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de l'envoi du rappel pour {$care->cat->name}: " . $e->getMessage());
        }
    }

    /**
     * Détermine l'email du destinataire selon la priorité
     */
    private function getRecipientEmail(MedicalCare $care): ?string
    {
        // 1. Responsable assigné
        if ($care->responsible) {
            if (property_exists($care->responsible, 'email') && $care->responsible->email) {
                return $care->responsible->email;
            }
        }

        // 2. Famille d'accueil si le chat est en séjour
        if ($care->cat->currentStay && $care->cat->currentStay->fosterFamily) {
            return $care->cat->currentStay->fosterFamily->email;
        }

        // 3. Email admin par défaut
        return config('mail.from.address');
    }
}
