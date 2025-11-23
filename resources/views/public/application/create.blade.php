@extends('layouts.public')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold text-primary">Candidature à l'adoption</h2>
                            @if($cat)
                                <p class="lead">Pour <span class="fw-bold text-dark">{{ $cat->name }}</span></p>
                            @else
                                <p class="text-muted">Candidature spontanée</p>
                            @endif
                        </div>

                        <form action="{{ route('public.submit') }}" method="POST">
                            @csrf
                            @if($cat)
                                <input type="hidden" name="cat_id" value="{{ $cat->id }}">
                            @endif

                            <h5 class="fw-bold mb-3 border-bottom pb-2">Vos coordonnées</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-12">
                                    <label class="form-label">Nom complet</label>
                                    <input type="text" name="applicant_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="applicant_email" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Téléphone</label>
                                    <input type="tel" name="applicant_phone" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Adresse complète</label>
                                    <textarea name="applicant_address" class="form-control" rows="2" required></textarea>
                                </div>
                            </div>

                            <h5 class="fw-bold mb-3 border-bottom pb-2">Votre foyer</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Type de logement</label>
                                    <select name="housing_type" class="form-select" required>
                                        <option value="apartment">Appartement</option>
                                        <option value="house">Maison</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mt-md-4 pt-2">
                                        <input class="form-check-input" type="checkbox" name="has_garden" value="1"
                                            id="gardenCheck">
                                        <label class="form-check-label" for="gardenCheck">
                                            J'ai un jardin / balcon sécurisé
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Composition de la famille (adultes, enfants...)</label>
                                    <input type="text" name="family_composition" class="form-control"
                                        placeholder="Ex: 2 adultes, 1 enfant de 5 ans" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Autres animaux au foyer ?</label>
                                    <input type="text" name="other_pets" class="form-control"
                                        placeholder="Ex: 1 chien, 1 chat âgé...">
                                </div>
                            </div>

                            <h5 class="fw-bold mb-3 border-bottom pb-2">Votre projet</h5>
                            <div class="mb-4">
                                <label class="form-label">Pourquoi souhaitez-vous adopter ?</label>
                                <textarea name="motivation" class="form-control" rows="4"
                                    placeholder="Racontez-nous votre projet, votre expérience avec les chats..."
                                    required></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">Envoyer ma
                                    candidature</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection