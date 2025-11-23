<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
        }

        .info-row {
            margin-bottom: 15px;
        }

        .label {
            font-weight: bold;
            color: #495057;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🔔 Rappel soin médical</h1>
        </div>

        <div class="content">
            <p>Bonjour,</p>

            <p>Ceci est un rappel pour le soin médical suivant :</p>

            <div class="info-row">
                <span class="label">Chat :</span> {{ $medicalCare->cat->name }}
            </div>

            <div class="info-row">
                <span class="label">Type de soin :</span>
                @php
                    $types = [
                        'vaccination' => 'Vaccination',
                        'deworming' => 'Vermifuge',
                        'vet_visit' => 'Visite vétérinaire',
                        'sterilization' => 'Stérilisation',
                        'other' => 'Autre'
                    ];
                @endphp
                {{ $types[$medicalCare->type] ?? $medicalCare->type }}
            </div>

            <div class="info-row">
                <span class="label">Titre :</span> {{ $medicalCare->title }}
            </div>

            <div class="info-row">
                <span class="label">Date prévue :</span> {{ $medicalCare->care_date->format('d/m/Y') }}
            </div>

            @if($medicalCare->description)
                <div class="info-row">
                    <span class="label">Description :</span><br>
                    {{ $medicalCare->description }}
                </div>
            @endif

            @if($medicalCare->partner)
                <div class="info-row">
                    <span class="label">Vétérinaire :</span> {{ $medicalCare->partner->name }}
                    @if($medicalCare->partner->phone)
                        ({{ $medicalCare->partner->phone }})
                    @endif
                </div>
            @endif

            @if($medicalCare->notes)
                <div class="info-row">
                    <span class="label">Notes :</span><br>
                    {{ $medicalCare->notes }}
                </div>
            @endif
        </div>

        <div class="footer">
            <p>Email envoyé automatiquement par ChatGuardian</p>
        </div>
    </div>
</body>

</html>