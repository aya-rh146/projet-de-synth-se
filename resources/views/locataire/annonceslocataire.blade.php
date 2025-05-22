@extends('layouts.app')

@section('content')

<div class="container mx-auto mt-10">

    {{-- Message succès --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5">
            {{ session('success') }}
        </div>
    @endif

    {{-- Formulaire création --}}
    <div class="bg-white shadow-md rounded-lg p-8 mb-10">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-8">Créer une annonce :</h2>

        <form id="form-annonce" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="titre_anno" class="block mb-2 font-semibold text-gray-700">Titre</label>
                    <input type="text" name="titre_anno" id="titre_anno" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                </div>
                <div>
                    <label for="type_log" class="block mb-2 font-semibold text-gray-700">Type de logement</label>
                    <select name="type_log" id="type_log" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                        <option value="studio">Studio</option>
                        <option value="appartement">Appartement</option>
                        <option value="maison">Maison</option>
                    </select>
                </div>
                <div>
                    <label for="prix_log" class="block mb-2 font-semibold text-gray-700">Budget (MAD/mois)</label>
                    <input type="number" name="prix_log" id="prix_log" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                </div>
                <div>
                    <label for="ville" class="block mb-2 font-semibold text-gray-700">Ville</label>
                    <input type="text" name="ville" id="ville" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                </div>
                <div>
                    <label for="nombre_colocataire_log" class="block mb-2 font-semibold text-gray-700">Nombre de colocataires</label>
                    <input type="number" name="nombre_colocataire_log" id="nombre_colocataire_log" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                </div>
                <div>
                    <label for="localisation_log" class="block mb-2 font-semibold text-gray-700">Localisation</label>
                    <input type="text" name="localisation_log" id="localisation_log" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                </div>
            </div>
            <div class="mt-6">
                <label for="description_anno" class="block mb-2 font-semibold text-gray-700">Description</label>
                <textarea name="description_anno" id="description_anno" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required></textarea>
            </div>
            <div class="flex justify-center mt-8">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-8 rounded-full">
                    Lancer l'annonce
                </button>
            </div>
        </form>
    </div>

    {{-- Liste des annonces --}}
    <h2 class="text-center mb-5 text-2xl font-bold text-gray-800">Liste des annonces</h2>

    <div class="container">
        <div class="row" id="annoncesList">
            @forelse ($annonces as $annonce)
                <div class="col-md-4 mb-4 d-flex align-items-stretch">
                    <div class="card h-100 shadow-sm border-0 w-100" style="border-radius: 10px;">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title fw-bold fs-4 mb-3">{{ $annonce->titre_anno }}</h5>
                                <p class="card-text text-muted">{{ $annonce->description_anno }}</p>
                                <p><strong>💰 Prix:</strong> {{ $annonce->logement->prix_log ?? 'N/A' }} MAD/mois</p>
                                <p><strong>📍 Localisation:</strong> {{ $annonce->logement->localisation_log ?? 'N/A' }}</p>
                                <p><strong>🗓 Publiée le:</strong> {{ \Carbon\Carbon::parse($annonce->date_publication_anno)->format('d F Y') }}</p>
                                <p><strong>👥 Nombre de colocataires:</strong> {{ $annonce->logement->nombre_colocataire_log ?? 'N/A' }}</p>
                                <p><strong>🏠 Type:</strong> {{ $annonce->logement->type_log ?? 'N/A' }}</p>
                                <p><strong>🌆 Ville:</strong> {{ $annonce->logement->ville ?? 'N/A' }}</p>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <form action="{{ route('locataire.modifierannoncelocataire.edit', $annonce->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn text-dark fw-bold px-3" style="background-color:rgb(235, 223, 213);">Modifier</button>
                                </form>
                                <form action="{{ route('locataire.annoncelocataire.destroy', $annonce->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn text-dark fw-bold px-3" style="background-color: #EBDFD5;">
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
        </div>

        <div class="mt-5">
            {{ $annonces->links() }}
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.getElementById('form-annonce').addEventListener('submit', function(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    axios.post('/locataire/annonceslocataire', formData, {
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'multipart/form-data',
        }
    })
    .then(response => {
        console.log(response.data);
        const json = response.data;
        form.reset();

        const newAnnonceHTML = `
        <div class="col-md-4 mb-4 d-flex align-items-stretch">
            <div class="card h-100 shadow-sm border-0 w-100" style="border-radius: 10px;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title fw-bold fs-4 mb-3">${json.annonce.titre_anno}</h5>
                        <p class="card-text text-muted">${json.annonce.description_anno}</p>
                        <p><strong>💰 Prix:</strong> ${json.annonce.logement.prix_log ?? 'N/A'} MAD/mois</p>
                        <p><strong>📍 Localisation:</strong> ${json.annonce.logement.localisation_log ?? 'N/A'}</p>
                        <p><strong>🗓 Publiée le:</strong> ${json.annonce.date_publication_anno}</p>
                        <p><strong>👥 Nombre de colocataires:</strong> ${json.annonce.logement.nombre_colocataire_log ?? 'N/A'}</p>
                        <p><strong>🏠 Type:</strong> ${json.annonce.logement.type_log ?? 'N/A'}</p>
                        <p><strong>🌆 Ville:</strong> ${json.annonce.logement.ville ?? 'N/A'}</p>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="/locataire/annonceslocataire/${json.annonce.id}/edit" class="btn text-dark fw-bold px-3" style="background-color: #EBDFD5;">
                            Modifier
                        </a>
                        <form action="/locataire/annonceslocataire/${json.annonce.id}" method="POST" onsubmit="return confirm('Confirmer la suppression?');">
                            <input type="hidden" name="_token" value="${token}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn text-dark fw-bold px-3" style="background-color: #EBDFD5;">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>`;

        document.getElementById('annoncesList').insertAdjacentHTML('afterbegin', newAnnonceHTML);
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'ajout: ' + (error.response?.data?.message || error.message));
    });
});
</script>
@endpush