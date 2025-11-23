@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-1"><a href="{{ route('medical-cares.index') }}"
                    class="text-decoration-none text-muted">Soins</a> / Édition</p>
            <h1 class="h3 fw-bold">Modifier le soin</h1>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('medical-cares.update', $medicalCare) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="cat_id" class="form-label">Chat <span class="text-danger">*</span></label>
                        <select class="form-select @error('cat_id') is-invalid @enderror" id="cat_id" name="cat_id" required>
                            @foreach($cats as $cat)
                                <option value="{{ $cat->id }}" {{ old('cat_id', $medicalCare->cat_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('cat_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="vaccination" {{ old('type', $medicalCare->type) == 'vaccination' ? 'selected' : '' }}>Vaccination</option>
                            <option value="deworming" {{ old('type', $medicalCare->type) == 'deworming' ? 'selected' : '' }}>Vermifuge</option>
                            <option value="vet_visit" {{ old('type', $medicalCare->type) == 'vet_visit' ? 'selected' : '' }}>Visite vétérinaire</option>
                            <option value="sterilization" {{ old('type', $medicalCare->type) == 'sterilization' ? 'selected' : '' }}>Stérilisation</option>
                            <option value="other" {{ old('type', $medicalCare->type) == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="title" class="form-label">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title', $medicalCare->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="2">{{ old('description', $medicalCare->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="care_date" class="form-label">Date du soin <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('care_date') is-invalid @enderror" id="care_date"
                            name="care_date" value="{{ old('care_date', $medicalCare->care_date->format('Y-m-d')) }}" required>
                        @error('care_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="next_due_date" class="form-label">Prochaine échéance</label>
                        <input type="date" class="form-control @error('next_due_date') is-invalid @enderror" id="next_due_date"
                            name="next_due_date" value="{{ old('next_due_date', optional($medicalCare->next_due_date)->format('Y-m-d')) }}">
                        @error('next_due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="scheduled" {{ old('status', $medicalCare->status) == 'scheduled' ? 'selected' : '' }}>Prévu</option>
                            <option value="completed" {{ old('status', $medicalCare->status) == 'completed' ? 'selected' : '' }}>Effectué</option>
                            <option value="cancelled" {{ old('status', $medicalCare->status) == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="partner_id" class="form-label">Vétérinaire</label>
                        <select class="form-select @error('partner_id') is-invalid @enderror" id="partner_id" name="partner_id">
                            <option value="">Aucun</option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}" {{ old('partner_id', $medicalCare->partner_id) == $partner->id ? 'selected' : '' }}>
                                    {{ $partner->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('partner_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="cost" class="form-label">Coût (€)</label>
                        <input type="number" class="form-control @error('cost') is-invalid @enderror" id="cost"
                            name="cost" value="{{ old('cost', $medicalCare->cost) }}" min="0" step="0.01">
                        @error('cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="responsible_type" class="form-label">Type de responsable</label>
                        <select class="form-select @error('responsible_type') is-invalid @enderror" id="responsible_type" name="responsible_type">
                            <option value="">Aucun</option>
                            <option value="App\Models\FosterFamily" {{ old('responsible_type', $medicalCare->responsible_type) == 'App\Models\FosterFamily' ? 'selected' : '' }}>Famille d'accueil</option>
                            <option value="App\Models\Volunteer" {{ old('responsible_type', $medicalCare->responsible_type) == 'App\Models\Volunteer' ? 'selected' : '' }}>Bénévole</option>
                            <option value="App\Models\User" {{ old('responsible_type', $medicalCare->responsible_type) == 'App\Models\User' ? 'selected' : '' }}>Utilisateur</option>
                            <option value="App\Models\Adopter" {{ old('responsible_type', $medicalCare->responsible_type) == 'App\Models\Adopter' ? 'selected' : '' }}>Adoptant</option>
                        </select>
                        @error('responsible_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="responsible_id" class="form-label">Responsable</label>
                        <select class="form-select @error('responsible_id') is-invalid @enderror" id="responsible_id" name="responsible_id">
                            <option value="">Sélectionner d'abord le type</option>
                        </select>
                        <small class="text-muted">Sélectionnez d'abord le type de responsable ci-dessus.</small>
                        @error('responsible_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                            rows="3">{{ old('notes', $medicalCare->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 text-end mt-4">
                        <a href="{{ route('medical-cares.index') }}" class="btn btn-light border me-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const responsibleTypeSelect = document.getElementById('responsible_type');
            const responsibleIdSelect = document.getElementById('responsible_id');

            const responsibleData = {
                'App\\Models\\FosterFamily': @json($fosterFamilies),
                'App\\Models\\Volunteer': @json($volunteers),
                'App\\Models\\User': @json($users),
                'App\\Models\\Adopter': @json($adopters)
            };

            const currentResponsibleId = {{ old('responsible_id', $medicalCare->responsible_id ?? 'null') }};

            responsibleTypeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                
                // Clear current options
                responsibleIdSelect.innerHTML = '<option value="">Sélectionner...</option>';
                
                if (selectedType && responsibleData[selectedType]) {
                    const people = responsibleData[selectedType];
                    people.forEach(person => {
                        const option = document.createElement('option');
                        option.value = person.id;
                        // Use full_name for volunteers and foster families, name for others
                        const displayName = person.full_name || person.name || person.first_name + ' ' + (person.last_name || '');
                        option.textContent = displayName;
                        if (currentResponsibleId && person.id == currentResponsibleId) {
                            option.selected = true;
                        }
                        responsibleIdSelect.appendChild(option);
                    });
                    responsibleIdSelect.disabled = false;
                } else {
                    responsibleIdSelect.disabled = true;
                }
            });

            // Trigger change to load options
            if (responsibleTypeSelect.value) {
                responsibleTypeSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
