<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carnet de Santé - {{ $cat->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24pt;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12pt;
            opacity: 0.9;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            background: #f8f9fa;
            padding: 10px 15px;
            border-left: 4px solid #667eea;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 8px;
            width: 30%;
            background: #f8f9fa;
        }
        .info-value {
            display: table-cell;
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        .timeline-item {
            padding: 12px;
            margin-bottom: 10px;
            border-left: 3px solid #667eea;
            background: #f8f9fa;
        }
        .timeline-item h4 {
            font-size: 12pt;
            margin-bottom: 5px;
            color: #667eea;
        }
        .timeline-item .date {
            font-size: 9pt;
            color: #6c757d;
            margin-bottom: 5px;
        }
        .timeline-item .description {
            font-size: 10pt;
            margin-top: 5px;
        }
        .prescription-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 10px;
            margin-top: 8px;
            border-radius: 4px;
        }
        .prescription-box strong {
            color: #856404;
        }
        .weight-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .weight-table th {
            background: #667eea;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 10pt;
        }
        .weight-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #dee2e6;
            font-size: 10pt;
        }
        .weight-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            font-size: 9pt;
            color: #6c757d;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9pt;
            font-weight: bold;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        .badge-secondary {
            background: #e2e3e5;
            color: #383d41;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 CARNET DE SANTÉ</h1>
        <p>{{ $cat->name }}</p>
        <p style="font-size: 10pt;">ChatGuardian - Association de Protection des Chats</p>
    </div>

    <!-- Informations générales -->
    <div class="section">
        <div class="section-title">📋 Informations Générales</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nom</div>
                <div class="info-value">{{ $cat->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Sexe</div>
                <div class="info-value">{{ $cat->sex === 'male' ? 'Mâle' : ($cat->sex === 'female' ? 'Femelle' : 'Inconnu') }}</div>
            </div>
            @if($cat->birthdate)
            <div class="info-row">
                <div class="info-label">Date de naissance</div>
                <div class="info-value">{{ $cat->birthdate->format('d/m/Y') }} ({{ $cat->birthdate->age }} ans)</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Stérilisé</div>
                <div class="info-value">
                    {{ $cat->sterilized ? 'Oui' : 'Non' }}
                    @if($cat->sterilized && $cat->sterilized_at)
                        ({{ $cat->sterilized_at->format('d/m/Y') }})
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">FIV</div>
                <div class="info-value">{{ $cat->fiv_label }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">FELV</div>
                <div class="info-value">{{ $cat->felv_label }}</div>
            </div>
            @if($cat->latest_weight)
            <div class="info-row">
                <div class="info-label">Poids actuel</div>
                <div class="info-value">{{ $cat->latest_weight }} kg</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Historique du poids -->
    @if($cat->weightRecords->count() > 0)
    <div class="section">
        <div class="section-title">⚖️ Historique du Poids</div>
        <table class="weight-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Poids (kg)</th>
                    <th>Mesuré par</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cat->weightRecords->take(10) as $record)
                <tr>
                    <td>{{ $record->measured_at->format('d/m/Y') }}</td>
                    <td><strong>{{ $record->weight }}</strong></td>
                    <td>{{ $record->measured_by ?? '-' }}</td>
                    <td>{{ $record->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Vaccinations -->
    @php
        $vaccinations = $cat->medicalCares->where('type', 'vaccination')->sortByDesc('care_date');
    @endphp
    @if($vaccinations->count() > 0)
    <div class="section">
        <div class="section-title">💉 Vaccinations</div>
        @foreach($vaccinations as $vacc)
        <div class="timeline-item">
            <h4>{{ $vacc->title }}</h4>
            <div class="date">
                📅 {{ $vacc->care_date->format('d/m/Y') }}
                @if($vacc->partner)
                    • 🏥 {{ $vacc->partner->name }}
                @endif
                <span class="badge badge-{{ $vacc->status === 'completed' ? 'success' : 'warning' }}">
                    {{ ucfirst($vacc->status) }}
                </span>
            </div>
            @if($vacc->description)
            <div class="description">{{ $vacc->description }}</div>
            @endif
            @if($vacc->next_due_date)
            <div style="margin-top: 5px; font-size: 9pt; color: #856404;">
                ⏰ Rappel prévu: {{ $vacc->next_due_date->format('d/m/Y') }}
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <!-- Historique médical complet -->
    <div class="section">
        <div class="section-title">🏥 Historique Médical Complet</div>
        @forelse($cat->medicalCares->sortByDesc('care_date') as $care)
        <div class="timeline-item">
            <h4>
                @switch($care->type)
                    @case('vaccination') 💉 @break
                    @case('sterilization') ✂️ @break
                    @case('deworming') 💊 @break
                    @case('vet_visit') 🏥 @break
                    @default 📋
                @endswitch
                {{ $care->title }}
            </h4>
            <div class="date">
                📅 {{ $care->care_date->format('d/m/Y') }}
                @if($care->partner)
                    • 🏥 {{ $care->partner->name }}
                @endif
                <span class="badge badge-{{ $care->status === 'completed' ? 'success' : ($care->status === 'scheduled' ? 'warning' : 'secondary') }}">
                    {{ ucfirst($care->status) }}
                </span>
            </div>
            
            @if($care->description)
            <div class="description">{{ $care->description }}</div>
            @endif

            @if($care->prescription)
            <div class="prescription-box">
                <strong>📋 Prescription:</strong> {{ $care->prescription }}
                @if($care->dosage)
                    <br><strong>Dosage:</strong> {{ $care->dosage }}
                @endif
                @if($care->duration)
                    <br><strong>Durée:</strong> {{ $care->duration }}
                @endif
            </div>
            @endif

            <div style="margin-top: 5px; font-size: 9pt; color: #6c757d;">
                @if($care->weight_at_visit)
                    ⚖️ Poids: {{ $care->weight_at_visit }} kg
                @endif
                @if($care->cost)
                    • 💰 Coût: {{ number_format($care->cost, 2) }} €
                @endif
            </div>
        </div>
        @empty
        <p style="text-align: center; color: #6c757d; padding: 20px;">Aucun soin médical enregistré</p>
        @endforelse
    </div>

    <!-- Vétérinaires -->
    @php
        $vets = $cat->medicalCares->pluck('partner')->filter()->unique('id');
    @endphp
    @if($vets->count() > 0)
    <div class="section">
        <div class="section-title">👨‍⚕️ Vétérinaires</div>
        @foreach($vets as $vet)
        <div style="padding: 10px; margin-bottom: 8px; background: #f8f9fa; border-radius: 4px;">
            <strong>{{ $vet->name }}</strong>
            @if($vet->phone)
                <br>📞 {{ $vet->phone }}
            @endif
            @if($vet->email)
                <br>✉️ {{ $vet->email }}
            @endif
            @if($vet->address)
                <br>📍 {{ $vet->address }}, {{ $vet->city }}
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <div class="footer">
        <p><strong>ChatGuardian</strong> - Association de Protection des Chats</p>
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Pour toute question, contactez-nous à admin@gestizen.pattechatmama.org</p>
    </div>
</body>
</html>
