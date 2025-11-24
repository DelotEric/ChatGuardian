@extends('layouts.app')

@section('content')
    <style>
        .help-section {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .help-section:last-child {
            border-bottom: none;
        }

        .action-step {
            background: #f8f9fa;
            padding: 0.75rem;
            border-left: 3px solid #3a7e8c;
            margin: 0.5rem 0;
            border-radius: 0 4px 4px 0;
        }

        .feature-badge {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
    </style>

    <div class="mb-4">
        <h1 class="h3 fw-bold"><i class="bi bi-question-circle-fill text-primary"></i> Centre d'aide</h1>
        <p class="text-muted">Manuel d'utilisation complet de ChatGuardian</p>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4" id="helpTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button">
                <i class="bi bi-book"></i> Manuel
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq" type="button">
                <i class="bi bi-patch-question"></i> FAQ
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="support-tab" data-bs-toggle="tab" data-bs-target="#support" type="button">
                <i class="bi bi-headset"></i> Support
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Manuel -->
        <div class="tab-pane fade show active" id="manual">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <!-- Chats -->
                    <div class="help-section">
                        <h4 class="text-primary mb-3"><i class="bi bi-heart-fill"></i> Gestion des Chats</h4>

                        <h6 class="fw-bold">➕ Ajouter un chat</h6>
                        <div class="action-step">
                            <strong>Menu</strong> → Chats → <span class="badge bg-primary">+ Nouveau chat</span> →
                            Remplissez le formulaire (nom, sexe, date de naissance, couleur, statut, santé)
                        </div>
                        <p class="small text-muted">Statuts : À l'adoption, Adopté, En famille d'accueil, En observation,
                            Disparu, Décédé</p>

                        <h6 class="fw-bold mt-3">📸 Gérer les photos</h6>
                        <div class="action-step">
                            Fiche du chat → <span class="badge bg-info">Voir photos</span> → <span
                                class="badge bg-success">Ajouter une photo</span> → La première devient photo principale
                        </div>

                        <h6 class="fw-bold mt-3">✏️ Modifier / Supprimer</h6>
                        <div class="action-step">
                            Liste des chats → Cliquez sur un chat → Boutons <span class="badge bg-warning">Modifier</span>
                            ou <span class="badge bg-danger">Supprimer</span>
                        </div>
                    </div>

                    <!-- Soins médicaux -->
                    <div class="help-section">
                        <h4 class="text-primary mb-3"><i class="bi bi-clipboard2-pulse"></i> Soins Médicaux <span
                                class="feature-badge bg-success text-white">Alertes</span></h4>

                        <h6 class="fw-bold">💉 Enregistrer un soin</h6>
                        <div class="action-step">
                            <strong>Menu</strong> → Soins médicaux → <span class="badge bg-primary">+ Nouveau soin</span> →
                            Sélectionnez chat, type (vaccination, vermifuge, visite, stérilisation), date et partenaire
                        </div>

                        <h6 class="fw-bold mt-3">👤 Assigner un responsable</h6>
                        <div class="action-step">
                            Dans le formulaire → <strong>Type de responsable</strong> → Choisir (Famille d'accueil,
                            Bénévole, Utilisateur, Adoptant) → Sélectionner la personne
                        </div>

                        <h6 class="fw-bold mt-3">📧 Envoyer une alerte email</h6>
                        <div class="action-step">
                            Fiche du soin → <span class="badge bg-warning">📧 Envoyer alerte email</span> → Le responsable
                            reçoit un email avec tous les détails
                        </div>

                        <h6 class="fw-bold mt-3">📊 Alertes Dashboard</h6>
                        <p class="small">• <span class="badge bg-danger">Soins en retard</span> affichés en rouge</p>
                        <p class="small">• <span class="badge bg-warning">Soins cette semaine</span> affichés en jaune</p>

                        <h6 class="fw-bold mt-3">🔔 Rappels automatiques <span
                                class="feature-badge bg-success text-white">Nouveau</span></h6>
                        <div class="action-step">
                            Le système envoie automatiquement des rappels par email :<br>
                            • <strong>7 jours avant</strong> le soin prévu<br>
                            • <strong>3 jours avant</strong> le soin prévu<br>
                            • <strong>Le jour même</strong> du soin<br>
                            • <strong>Alerte urgente</strong> pour les soins en retard
                        </div>
                        <p class="small text-info">✨ Les rappels sont envoyés quotidiennement à 9h00 au responsable assigné
                        </p>
                    </div>

                    <!-- Historique médical -->
                    <div class="help-section">
                        <h4 class="text-primary mb-3"><i class="bi bi-clipboard2-pulse"></i> Historique Médical <span
                                class="feature-badge bg-success text-white">Nouveau</span></h4>

                        <h6 class="fw-bold">📋 Consulter l'historique</h6>
                        <div class="action-step">
                            Fiche du chat → <span class="badge bg-info">📋 Historique médical</span> → Vue complète avec
                            :<br>
                            • <strong>Statistiques</strong> : Total soins, dernière vaccination, prochain soin, poids
                            actuel<br>
                            • <strong>Courbe de poids</strong> : Graphique d'évolution<br>
                            • <strong>Timeline médicale</strong> : Tous les soins avec prescriptions
                        </div>

                        <h6 class="fw-bold mt-3">⚖️ Ajouter une pesée</h6>
                        <div class="action-step">
                            Historique médical → Section "Historique des pesées" → <span class="badge bg-primary">+
                                Ajouter</span> →<br>
                            Remplir : Poids (kg), Date, Mesuré par, Notes<br>
                            <small class="text-muted">Les champs "Date" et "Mesuré par" sont pré-remplis</small>
                        </div>

                        <h6 class="fw-bold mt-3">✏️ Modifier/Supprimer une pesée</h6>
                        <div class="action-step">
                            Chaque pesée a des boutons <span class="badge bg-primary">✏️</span> Modifier et <span
                                class="badge bg-danger">🗑️</span> Supprimer
                        </div>

                        <h6 class="fw-bold mt-3">💊 Enregistrer une prescription</h6>
                        <div class="action-step">
                            Lors de la création/modification d'un soin → Remplir :<br>
                            • <strong>Prescription</strong> : Détails du traitement/médicament<br>
                            • <strong>Dosage</strong> : Instructions de dosage<br>
                            • <strong>Durée</strong> : Durée du traitement<br>
                            • <strong>Poids lors de la visite</strong> : Poids mesuré
                        </div>

                        <h6 class="fw-bold mt-3">📄 Générer le carnet de santé PDF</h6>
                        <div class="action-step">
                            Historique médical → <span class="badge bg-primary">📥 Télécharger carnet de santé PDF</span>
                            →<br>
                            Le PDF contient :<br>
                            • Informations du chat (nom, âge, stérilisation, FIV/FELV)<br>
                            • Historique du poids (tableau)<br>
                            • Vaccinations avec rappels<br>
                            • Timeline médicale complète avec prescriptions<br>
                            • Coordonnées des vétérinaires
                        </div>
                        <p class="small text-success">✨ Parfait pour les adoptions ou les visites vétérinaires !</p>
                    </div>

                    <!-- Points de nourrissage -->
                    <div class="help-section">
                        <h4 class="text-primary mb-3"><i class="bi bi-geo-alt-fill"></i> Points de Nourrissage <span
                                class="feature-badge bg-info text-white">Carte</span></h4>

                        <h6 class="fw-bold">📍 Créer un point</h6>
                        <div class="action-step">
                            <strong>Menu</strong> → Points de nourrissage → <span class="badge bg-primary">+ Nouveau
                                point</span> → Nom, adresse, latitude/longitude, fréquence, notes
                        </div>

                        <h6 class="fw-bold mt-3">👥 Assigner des bénévoles</h6>
                        <div class="action-step">
                            Fiche du point → Section "Bénévoles assignés" → <span class="badge bg-success">Assigner un
                                bénévole</span>
                        </div>

                        <h6 class="fw-bold mt-3">🗺️ Visualiser sur la carte</h6>
                        <div class="action-step">
                            Liste des points → <span class="badge bg-info">📍 Carte</span> → Tous les points s'affichent
                            avec marqueurs cliquables
                        </div>

                        <div class="mt-3 p-3 bg-light rounded border">
                            <h6 class="fw-bold text-primary"><i class="bi bi-google"></i> Comment récupérer Latitude /
                                Longitude ?</h6>
                            <ol class="small mb-0 ps-3">
                                <li>Allez sur <a href="https://www.google.com/maps" target="_blank">Google Maps</a>.</li>
                                <li>Faites un <strong>clic droit</strong> à l'endroit exact du point de nourrissage.</li>
                                <li>Cliquez sur les chiffres en haut du menu (ex: <code>48.8566, 2.3522</code>).</li>
                                <li>Cela copie automatiquement les coordonnées dans votre presse-papier !</li>
                                <li>Collez-les dans le champ <strong>Latitude</strong> (1er chiffre) et
                                    <strong>Longitude</strong> (2ème chiffre).
                                </li>
                            </ol>
                        </div>
                    </div>

                    <!-- Bénévoles & Familles -->
                    <div class="help-section">
                        <h4 class="text-primary mb-3"><i class="bi bi-people-fill"></i> Bénévoles & Familles d'Accueil</h4>

                        <h6 class="fw-bold">➕ Ajouter un bénévole</h6>
                        <div class="action-step">
                            <strong>Menu</strong> → Bénévoles → <span class="badge bg-primary">+ Nouveau bénévole</span> →
                            Nom, email, téléphone, disponibilités, compétences
                        </div>

                        <h6 class="fw-bold mt-3">🏠 Créer une famille d'accueil</h6>
                        <div class="action-step">
                            <strong>Menu</strong> → Familles d'accueil → <span class="badge bg-primary">+ Nouvelle
                                famille</span> → Coordonnées, <strong>capacité d'accueil</strong>, type de logement
                        </div>

                        <h6 class="fw-bold mt-3">📅 Gérer les séjours</h6>
                        <div class="action-step">
                            Fiche famille → Section "Séjours en cours" → <span class="badge bg-success">Nouveau
                                séjour</span> → Choisir le chat, dates début/fin, résultat
                        </div>
                    </div>

                    <!-- Adhérents & Cotisations -->
                    <div class="help-section">
                        <h4 class="text-primary mb-3"><i class="bi bi-person-badge"></i> Adhérents & Cotisations <span
                                class="feature-badge bg-success text-white">Reçus fiscaux</span></h4>

                        <h6 class="fw-bold">➕ Créer un adhérent</h6>
                        <div class="action-step">
                            <strong>Menu</strong> → Adhérents → <span class="badge bg-primary">+ Nouvel adhérent</span> → Le
                            <strong>numéro d'adhérent</strong> (ADH00001) est généré automatiquement
                        </div>

                        <h6 class="fw-bold mt-3">💳 Enregistrer une cotisation</h6>
                        <div class="action-step">
                            Fiche adhérent → Section "Cotisations" → <span class="badge bg-success">+ Nouvelle
                                cotisation</span> → Année, montant, date, mode de paiement<br>
                            <small class="text-muted">Le numéro de reçu (RF-2025-1-001) est généré automatiquement</small>
                        </div>

                        <h6 class="fw-bold mt-3">📄 Générer un reçu fiscal</h6>
                        <div class="action-step">
                            Fiche adhérent → Historique → <span class="badge bg-primary">📄 Reçu fiscal</span> → Le reçu
                            s'ouvre avec :<br>
                            • <span class="badge bg-info">📥 Télécharger PDF</span> (vrai PDF)<br>
                            • <span class="badge bg-success">📧 Envoyer par email</span><br>
                            • <span class="badge bg-secondary">🖨️ Imprimer</span>
                        </div>
                        <p class="small text-info">✨ Le reçu calcule automatiquement la réduction d'impôt (66% du montant)
                        </p>
                    </div>

                    <!-- Dons -->
                    <div class="help-section">
                        <h4 class="text-primary mb-3"><i class="bi bi-gift-fill"></i> Donateurs & Dons <span
                                class="feature-badge bg-success text-white">Reçus fiscaux</span></h4>

                        <h6 class="fw-bold">👤 Créer un donateur</h6>
                        <div class="action-step">
                            <strong>Menu</strong> → Dons → <span class="badge bg-primary">+ Nouveau don</span> → En bas du
                            formulaire : <span class="badge bg-info">Créer un donateur</span>
                        </div>

                        <h6 class="fw-bold mt-3">💰 Enregistrer un don</h6>
                        <div class="action-step">
                            <strong>Menu</strong> → Dons → <span class="badge bg-primary">+ Nouveau don</span> → Donateur,
                            montant, date, mode de paiement<br>
                            <small class="text-muted">Le numéro de reçu (RD-2025-001) est généré automatiquement</small>
                        </div>

                        <h6 class="fw-bold mt-3">📄 Générer un reçu fiscal</h6>
                        <div class="action-step">
                            Fiche don → Section "Reçu fiscal" → <span class="badge bg-primary">📄 Générer le reçu
                                fiscal</span> → Options :<br>
                            • <span class="badge bg-info">📥 Télécharger PDF</span><br>
                            • <span class="badge bg-success">📧 Envoyer par email</span> (marque automatiquement comme
                            "envoyé")<br>
                            • <span class="badge bg-secondary">🖨️ Imprimer</span>
                        </div>
                    </div>

                    <!-- Inventaire -->
                    <div class="help-section">
                        <h4 class="text-primary mb-3"><i class="bi bi-box-seam"></i> Inventaire <span
                                class="feature-badge bg-warning text-dark">Alertes stock</span></h4>

                        <h6 class="fw-bold">📦 Créer un article</h6>
                        <div class="action-step">
                            <strong>Menu</strong> → Inventaire → <span class="badge bg-primary">+ Nouvel article</span> →
                            Nom, catégorie, quantité, unité, <strong>seuil minimal</strong>
                        </div>
                        <p class="small text-muted">Catégories : Nourriture, Médicaments, Équipement, Litière, Jouets, Autre
                        </p>

                        <h6 class="fw-bold mt-3">📊 Enregistrer des mouvements</h6>
                        <div class="action-step">
                            <strong>Menu</strong> → Mouvements → <span class="badge bg-primary">+ Nouveau mouvement</span>
                            →<br>
                            • <strong>Type</strong> : Entrée (achat, don) ou Sortie (usage, distribution)<br>
                            • <strong>Quantité</strong> : Le stock se met à jour automatiquement
                        </div>

                        <h6 class="fw-bold mt-3">⚠️ Alertes stock faible</h6>
                        <p class="small">• Articles en rouge dans la liste quand stock ≤ seuil</p>
                        <p class="small">• Widget d'alerte sur le dashboard</p>
                    </div>

                    <!-- Actualités & Événements -->
                    <div class="help-section">
                        <h4 class="text-primary mb-3"><i class="bi bi-newspaper"></i> Actualités & <i
                                class="bi bi-calendar-event"></i> Agenda</h4>

                        <h6 class="fw-bold">📰 Publier une actualité</h6>
                        <div class="action-step">
                            <strong>Menu</strong> → Actualités → <span class="badge bg-primary">+ Nouvelle actualité</span>
                            → Titre, contenu, date de publication<br>
                            • ☑️ <strong>Publier immédiatement</strong> ou garder en brouillon
                        </div>
                        <p class="small text-muted">Les 4 dernières actualités publiées s'affichent sur le dashboard</p>

                        <h6 class="fw-bold mt-3">📅 Créer un événement</h6>
                        <div class="action-step">
                            <strong>Menu</strong> → Agenda → <span class="badge bg-primary">+ Nouvel événement</span> →
                            Titre, description, date, heure, lieu<br>
                            • <strong>Type</strong> : Journée d'adoption (bleu), Formation (jaune), Réunion (gris)
                        </div>
                        <p class="small text-info">✨ Les 3 prochains événements actifs s'affichent sur le dashboard avec
                            badges colorés</p>
                    </div>

                    <!-- Messagerie -->
                    <div class="help-section">
                        <h4 class="text-primary mb-3"><i class="bi bi-envelope-fill"></i> Messagerie Interne <span
                                class="feature-badge bg-success text-white">Notifications email</span></h4>

                        <h6 class="fw-bold">✉️ Envoyer un message</h6>
                        <div class="action-step">
                            <strong>Menu</strong> → Messagerie → <span class="badge bg-primary">✉️ Nouveau message</span> →
                            Destinataire, sujet, message<br>
                            <small class="text-success">📧 Le destinataire reçoit automatiquement une notification par
                                email</small>
                        </div>

                        <h6 class="fw-bold mt-3">📬 Lire les messages</h6>
                        <div class="action-step">
                            • <strong>Boîte de réception</strong> : Messages reçus avec badge "Nouveau" pour les non lus<br>
                            • <strong>Messages envoyés</strong> : Historique de vos envois<br>
                            • <strong>Compteur</strong> : Badge rouge dans le header et menu avec nombre de messages non lus
                        </div>

                        <h6 class="fw-bold mt-3">↩️ Répondre</h6>
                        <div class="action-step">
                            Ouvrir le message → <span class="badge bg-primary">↩️ Répondre</span> → Le destinataire et sujet
                            sont pré-remplis
                        </div>

                        <h6 class="fw-bold mt-3">🗑️ Supprimer</h6>
                        <p class="small text-muted">Le message reste visible pour l'autre utilisateur (soft delete)</p>
                    </div>

                    <!-- Partenaires -->
                    <div class="help-section">
                        <h4 class="text-primary mb-3"><i class="bi bi-shop"></i> Partenaires</h4>

                        <h6 class="fw-bold">🤝 Ajouter un partenaire</h6>
                        <div class="action-step">
                            <strong>Menu</strong> → Partenaires → <span class="badge bg-primary">+ Nouveau partenaire</span>
                            → Type (Vétérinaire, Animalerie, Refuge...), coordonnées, services, remise
                        </div>
                    </div>

                    <!-- Adoptants -->
                    <div class="help-section">
                        <h4 class="text-primary mb-3"><i class="bi bi-people"></i> Adoptants</h4>

                        <h6 class="fw-bold">👥 Créer un adoptant</h6>
                        <div class="action-step">
                            <strong>Menu</strong> → Adoptants → <span class="badge bg-primary">+ Nouvel adoptant</span> →
                            Nom, email, téléphone, adresse
                        </div>

                        <h6 class="fw-bold mt-3">✅ Marquer un chat comme adopté</h6>
                        <div class="action-step">
                            Fiche du chat → Modifier → <strong>Statut</strong> : Adopté → <strong>Adoptant</strong> +
                            <strong>Date d'adoption</strong>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="tab-pane fade" id="faq">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Questions Fréquentes</h4>

                    <div class="accordion" id="faqAccordion">
                        <!-- Q1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq1">
                                    Comment voir les messages non lus ?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    Le badge rouge avec le nombre de messages non lus apparaît dans le
                                    <strong>header</strong> (icône enveloppe) et dans le <strong>menu Messagerie</strong>.
                                    Cliquez pour accéder à votre boîte de réception.
                                </div>
                            </div>
                        </div>

                        <!-- Q2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq2">
                                    Le PDF du reçu fiscal s'ouvre en HTML, c'est normal ?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    Non ! Les reçus fiscaux sont maintenant de <strong>vrais PDF</strong> générés avec
                                    dompdf. Utilisez le bouton <span class="badge bg-primary">📥 Télécharger PDF</span> pour
                                    obtenir un fichier PDF binaire lisible par n'importe quel lecteur.
                                </div>
                            </div>
                        </div>

                        <!-- Q3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq3">
                                    Comment savoir quels chats ont besoin de soins ?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    Sur le <strong>dashboard</strong>, les soins s'affichent dans la section "Alertes soins
                                    médicaux" :<br>
                                    • <span class="badge bg-danger">Rouge</span> : Soins en retard<br>
                                    • <span class="badge bg-warning">Jaune</span> : Soins cette semaine<br>
                                    Cliquez sur "Voir tous" pour accéder à la liste complète.
                                </div>
                            </div>
                        </div>

                        <!-- Q4 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq4">
                                    Le stock d'un article est négatif, pourquoi ?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    Cela arrive si vous enregistrez une <strong>sortie</strong> supérieure au stock
                                    disponible. Vérifiez l'historique des mouvements et ajoutez une <strong>entrée</strong>
                                    (achat, don) pour corriger.
                                </div>
                            </div>
                        </div>

                        <!-- Q5 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq5">
                                    Puis-je envoyer le reçu fiscal par email ?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    Oui ! Sur la page du reçu (adhérents ou dons), cliquez sur <span
                                        class="badge bg-success">📧 Envoyer par email</span>. Le PDF est envoyé en pièce
                                    jointe à l'email du donateur/adhérent. Le statut passe automatiquement à "Envoyé".
                                </div>
                            </div>
                        </div>

                        <!-- Q6 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq6">
                                    Comment configurer l'envoi d'emails ?
                                </button>
                            </h2>
                            <div id="faq6" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    Éditez le fichier <code>.env</code> :<br>
                                    <code>MAIL_MAILER=smtp</code><br>
                                    <code>MAIL_HOST=votre-serveur-smtp</code><br>
                                    <code>MAIL_PORT=587</code><br>
                                    <code>MAIL_USERNAME=votre-email</code><br>
                                    <code>MAIL_PASSWORD=mot-de-passe</code><br><br>
                                    Pour tester localement, utilisez <code>MAIL_MAILER=log</code> (emails dans storage/logs)
                                </div>
                            </div>
                        </div>

                        <!-- Q7 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq7">
                                    Où voir les chats disponibles à l'adoption ?
                                </button>
                            </h2>
                            <div id="faq7" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    Menu <strong>Chats</strong> → Filtrer par statut "À l'adoption". Le dashboard affiche
                                    aussi le nombre total sur la carte statistique.
                                </div>
                            </div>
                        </div>

                        <!-- Q8 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq8">
                                    Comment ajouter une pesée pour un chat ?
                                </button>
                            </h2>
                            <div id="faq8" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    Allez sur la fiche du chat → <span class="badge bg-info">📋 Historique médical</span> →
                                    Dans la section "Historique des pesées", cliquez sur <span class="badge bg-primary">+
                                        Ajouter</span>.<br>
                                    Remplissez le poids, la date (pré-remplie avec aujourd'hui), qui a mesuré (pré-rempli
                                    avec votre nom),
                                    et des notes optionnelles.
                                </div>
                            </div>
                        </div>

                        <!-- Q9 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq9">
                                    Comment générer un carnet de santé PDF pour un chat ?
                                </button>
                            </h2>
                            <div id="faq9" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    Fiche du chat → <span class="badge bg-info">📋 Historique médical</span> →
                                    <span class="badge bg-primary">📥 Télécharger carnet de santé PDF</span>.<br>
                                    Le PDF contient toutes les informations médicales : vaccinations, soins, poids,
                                    prescriptions,
                                    et coordonnées des vétérinaires. Parfait pour les adoptions !
                                </div>
                            </div>
                        </div>

                        <!-- Q10 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq10">
                                    Les rappels automatiques de soins sont-ils activés ?
                                </button>
                            </h2>
                            <div id="faq10" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    Oui ! Le système envoie automatiquement des rappels par email à 7 jours, 3 jours,
                                    et le jour même du soin prévu. Les soins en retard génèrent une alerte urgente.<br>
                                    Les rappels sont envoyés quotidiennement à 9h00 au responsable assigné au soin
                                    (ou à la famille d'accueil si le chat est en séjour).
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support -->
        <div class="tab-pane fade" id="support">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Support & Contact</h4>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-primary h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-envelope-fill"></i> Email</h5>
                                    <p>Pour toute question ou problème technique :</p>
                                    <p class="fw-bold text-primary">support@chatguardian.fr</p>
                                    <p class="text-muted small mb-0">Réponse sous 48h ouvrées</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card border-success h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-github"></i> GitHub</h5>
                                    <p>Signalez un bug ou proposez une amélioration :</p>
                                    <a href="#" class="btn btn-outline-success btn-sm">Issues GitHub</a>
                                    <p class="text-muted small mb-0 mt-2">Projet open-source</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Informations système</h6>
                        <p class="mb-1"><strong>Version :</strong> ChatGuardian 1.0.0</p>
                        <p class="mb-1"><strong>Laravel :</strong> {{ app()->version() }}</p>
                        <p class="mb-0"><strong>PHP :</strong> {{ PHP_VERSION }}</p>
                    </div>

                    <h5 class="mt-4">Ressources utiles</h5>
                    <ul>
                        <li><a href="https://laravel.com/docs" target="_blank">Documentation Laravel</a></li>
                        <li><a href="https://getbootstrap.com/docs" target="_blank">Documentation Bootstrap</a></li>
                        <li><a href="https://icons.getbootstrap.com" target="_blank">Bootstrap Icons</a></li>
                    </ul>

                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="fw-bold"><i class="bi bi-lightbulb"></i> Astuce</h6>
                        <p class="mb-0 small">Utilisez le <strong>dashboard</strong> comme point central pour surveiller
                            l'activité : alertes soins, stock faible, actualités et événements à venir sont tous visibles en
                            un coup d'œil !</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection