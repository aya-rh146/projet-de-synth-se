@extends('layouts.app')

@section('content')

<div class="container mx-auto mt-10">
    {{-- Message succès --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Message erreur --}}
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Formulaire création --}}
    <div class="card shadow-md rounded-lg mb-5">
        <div class="card-body p-5">
            <h2 class="text-center mb-4 fw-bold text-primary">Créer une annonce :</h2>

            <form id="form-annonce" method="POST" action="{{ route('locataire.annoncelocataire.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-3">
                        <label for="titre_anno" class="block mb-2 font-semibold text-gray-700">Titre</label>
                        <input type="text" name="titre_anno" id="titre_anno" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" value="{{ old('titre_anno') }}" required>
                        @error('titre_anno')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="type_log" class="block mb-2 font-semibold text-gray-700">Type de logement</label>
                        <select name="type_log" id="type_log" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                            <option value="studio" {{ old('type_log') == 'studio' ? 'selected' : '' }}>Studio</option>
                            <option value="appartement" {{ old('type_log') == 'appartement' ? 'selected' : '' }}>Appartement</option>
                            <option value="maison" {{ old('type_log') == 'maison' ? 'selected' : '' }}>Maison</option>
                        </select>
                        @error('type_log')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="prix_log" class="block mb-2 font-semibold text-gray-700">Budget (MAD/mois)</label>
                        <input type="number" name="prix_log" id="prix_log" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" value="{{ old('prix_log') }}" required>
                        @error('prix_log')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="ville" class="block mb-2 font-semibold text-gray-700">Ville</label>
                        <input type="text" name="ville" id="ville" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" value="{{ old('ville') }}" required>
                        @error('ville')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="nombre_colocataire_log" class="block mb-2 font-semibold text-gray-700">Nombre de colocataires</label>
                        <input type="number" name="nombre_colocataire_log" id="nombre_colocataire_log" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" value="{{ old('nombre_colocataire_log') }}" required>
                        @error('nombre_colocataire_log')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="localisation_log" class="block mb-2 font-semibold text-gray-700">Localisation</label>
                        <input type="text" name="localisation_log" id="localisation_log" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" value="{{ old('localisation_log') }}" required>
                        @error('localisation_log')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description_anno" class="block mb-2 font-semibold text-gray-700">Description</label>
                    <textarea name="description_anno" id="description_anno" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>{{ old('description_anno') }}</textarea>
                    @error('description_anno')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg">Lancer l'annonce</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Liste des annonces --}}
    <h2 class="text-center mb-5 fw-bold text-primary" style="font-size: 28px;">Liste des annonces</h2>

    <div class="container">
        <div class="row" id="annoncesList">
            @if(isset($annonces) && $annonces->count() > 0)
                @forelse ($annonces as $annonce)
                    <div class="col-md-4 mb-4 d-flex align-items-stretch">
                        <div class="card h-100 shadow-sm border-0 w-100" style="border-radius: 10px;">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title fw-bold fs-4 mb-3 text-lowercase">{{ $annonce->titre_anno }}</h5>
                                    <div class="mb-3">
                                        <p class="card-text text-muted">{{ $annonce->description_anno }}</p>
                                        <p class="mb-1"><span class="me-2">💰</span><strong>Prix:</strong> {{ $annonce->logement->prix_log ?? 'N/A' }} MAD/mois</p>
                                        <p class="mb-1"><span class="me-2">📍</span><strong>Localisation:</strong> {{ $annonce->logement->localisation_log ?? 'N/A' }}</p>
                                        <p class="mb-1"><span class="me-2">🗓</span><strong>Publiée le:</strong> {{ \Carbon\Carbon::parse($annonce->date_publication_anno)->format('d F Y') }}</p>
                                        <p class="mb-1"><span class="me-2">👥</span><strong>Nombre de colocataires:</strong> {{ $annonce->logement->nombre_colocataire_log ?? 'N/A' }}</p>
                                        <p class="mb-1"><span class="me-2">🏠</span><strong>Type:</strong> {{ $annonce->logement->type_log ?? 'N/A' }}</p>
                                        <p class="mb-1"><span class="me-2">🌆</span><strong>Ville:</strong> {{ $annonce->logement->ville ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('locataire.modifierannoncelocataire.edit', $annonce->id) }}" class="btn text-dark fw-bold px-3 flex-fill mx-1" style="background-color: #EBDFD5;">
                                    Modifier
                                </a>
                                    <form action="{{ route('locataire.annoncelocataire.destroy', $annonce->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression?');" class="flex-fill mx-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn text-dark fw-bold px-3 w-100" style="background-color: #EBDFD5;">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-5">
                        Aucun annonce disponible pour le moment.
                    </div>
                @endforelse
            @else
                <div class="text-center text-gray-500 py-5">
                    Aucun annonce disponible pour le moment.
                </div>
            @endif
        </div>

        {{-- Pagination personnalisée --}}
        @if(isset($annonces) && $annonces->hasPages())
        <div class="mt-5 text-center">
            <nav aria-label="Pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $annonces->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $annonces->previousPageUrl() }}">Précédent</a>
                    </li>
                    @foreach($annonces->getUrlRange(1, $annonces->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $annonces->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @endforeach
                    <li class="page-item {{ $annonces->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $annonces->nextPageUrl() }}">Suivant</a>
                    </li>
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-annonce');

    if (!form) {
        console.error('Formulaire #form-annonce introuvable');
        return;
    }

    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        const formData = new FormData(form);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

        try {
            const response = await axios.post('/locataire/annonceslocataire', formData, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'multipart/form-data',
                }
            });

            const json = response.data;

            if (json.success) {
                const photosList = json.annonce.logement.photos ? JSON.parse(json.annonce.logement.photos) : [];
                const firstPhoto = photosList.length > 0 ? photosList[0] : null;
                const photosHtml = firstPhoto
                    ? `<div class="mb-3"><img src="/storage/${firstPhoto}" alt="Photo de l'annonce" class="rounded w-100" style="height: 150px; object-fit: cover;"></div>`
                    : `<p class="text-muted mb-3">Aucune photo disponible</p>`;

                const newAnnonceHTML = `
                <div class="col-md-4 mb-4 d-flex align-items-stretch">
                    <div class="card h-100 shadow-sm border-0 w-100" style="border-radius: 10px;">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title fw-bold fs-4 mb-3 text-lowercase">${json.annonce.titre_anno}</h5>
                                ${photosHtml}
                                <div class="mb-3">
                                    <p class="card-text text-muted">${json.annonce.description_anno}</p>
                                    <p class="mb-1"><span class="me-2">💰</span><strong>Prix:</strong> ${json.annonce.logement.prix_log ?? 'N/A'} MAD/mois</p>
                                    <p class="mb-1"><span class="me-2">📍</span><strong>Localisation:</strong> ${json.annonce.logement.localisation_log ?? 'N/A'}</p>
                                    <p class="mb-1"><span class="me-2">🗓</span><strong>Publiée le:</strong> ${new Date(json.annonce.date_publication_anno).toLocaleDateString('fr-FR')}</p>
                                    <p class="mb-1"><span class="me-2">👥</span><strong>Nombre de colocataires:</strong> ${json.annonce.logement.nombre_colocataire_log ?? 'N/A'}</p>
                                    <p class="mb-1"><span class="me-2">🏠</span><strong>Type:</strong> ${json.annonce.logement.type_log ?? 'N/A'}</p>
                                    <p class="mb-1"><span class="me-2">🌆</span><strong>Ville:</strong> ${json.annonce.logement.ville ?? 'N/A'}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="/locataire/annonceslocataire/${json.annonce.id}/edit" class="btn text-dark fw-bold px-3 flex-fill mx-1" style="background-color: #EBDFD5;">
                                    Modifier
                                </a>
                                <form action="/locataire/annonceslocataire/${json.annonce.id}" method="POST" onsubmit="return confirm('Confirmer la suppression?');" class="flex-fill mx-1">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn text-dark fw-bold px-3 w-100" style="background-color: #EBDFD5;">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>`;

                const annoncesList = document.getElementById('annoncesList');
                if (annoncesList) {
                    annoncesList.insertAdjacentHTML('afterbegin', newAnnonceHTML);
                } else {
                    console.error('Conteneur #annoncesList introuvable');
                }

                form.reset();
                alert(json.message || 'Annonce créée avec succès !');

                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                alert('Erreur: ' + json.message);
            }
        } catch (error) {
            console.error('Erreur détaillée:', error);
            if (error.response) {
                const status = error.response.status;
                const data = error.response.data;
                let errorMessage = `Erreur ${status}: `;
                if (status === 422) {
                    errorMessage += Object.values(data.errors || {}).flat().join('\n') || data.message;
                } else {
                    errorMessage += data.message || 'Problème de connexion ou serveur.';
                }
                alert(errorMessage);
            } else {
                alert('Erreur: Vérifiez la console pour plus de détails.');
            }
        }
    });
});
</script>
@endpush