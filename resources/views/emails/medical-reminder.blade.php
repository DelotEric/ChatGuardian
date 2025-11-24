<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .alert-box {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .alert-overdue {
            background: #fee;
            border-left: 4px solid #dc3545;
        }
        .alert-today {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        .alert-soon {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
        }
        .info-row {
            margin: 10px 0;
            padding: 10px;
            background: white;
            border-radius: 5px;
        }
        .label {
            font-weight: bold;
            color: #667eea;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 Rappel Soin Médical</h1>
        <p>ChatGuardian - Gestion des soins</p>
    </div>

    <div class="content">
        <div class="alert-box alert-{{ $urgencyLevel }}">
            @if($urgencyLevel === 'overdue')
                <strong>🚨 URGENT:</strong> Ce soin est en retard de {{ abs($daysUntilDue) }} jour(s)
            @elseif($urgencyLevel === 'today')
                <strong>⚠️ AUJOURD'HUI:</strong> Ce soin est prévu pour aujourd'hui
            @elseif($daysUntilDue <= 3)
                <strong>📅 BIENTÔT:</strong> Ce soin est prévu dans {{ $daysUntilDue }} jour(s)
            @else
                <strong>📅 RAPPEL:</strong> Ce soin est prévu dans {{ $daysUntilDue }} jour(s)
            @endif
        </div>

        <h2>Informations du soin</h2>

        <div class="info-row">
            <span class="label">Chat:</span> {{ $medicalCare->cat->name }}
        </div>

        <div class="info-row">
            <span class="label">Type de soin:</span> 
            @switch($medicalCare->type)
                @case('vaccination')
                    💉 Vaccination
                    @break
                @case('deworming')
                    💊 Vermifuge
                    @break
                @case('sterilization')
                    ✂️ Stérilisation
                    @break
                @case('vet_visit')
                    🏥 Visite vétérinaire
                    @break
                @default
                    📋 {{ $medicalCare->type }}
            @endswitch
        </div>

        <div class="info-row">
            <span class="label">Titre:</span> {{ $medicalCare->title }}
        </div>

        @if($medicalCare->description)
        <div class="info-row">
            <span class="label">Description:</span> {{ $medicalCare->description }}
        </div>
        @endif

        <div class="info-row">
            <span class="label">Date prévue:</span> 
            <strong>{{ $medicalCare->care_date->format('d/m/Y') }}</strong>
            ({{ $medicalCare->care_date->locale('fr')->isoFormat('dddd D MMMM YYYY') }})
        </div>

        @if($medicalCare->partner)
        <div class="info-row">
            <span class="label">Vétérinaire:</span> {{ $medicalCare->partner->name }}
            @if($medicalCare->partner->phone)
                <br><small>📞 {{ $medicalCare->partner->phone }}</small>
            @endif
        </div>
        @endif

        @if($medicalCare->notes)
        <div class="info-row">
            <span class="label">Notes:</span> {{ $medicalCare->notes }}
        </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ url('/medical-cares/' . $medicalCare->id) }}" class="button">
                Voir le détail du soin
            </a>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement par ChatGuardian</p>
            <p>Pour toute question, contactez l'administration</p>
        </div>
    </div>
</body>
</html>
