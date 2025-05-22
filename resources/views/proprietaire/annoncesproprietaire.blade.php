@extends('layouts.app')

@section('content')

<div class="container mx-auto mt-10">

    {{-- Message succès --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5">
        {{ session('success') }}
    </div>
    @endif

    {{-- Formulaire: Propriétaire (بالضبط بحال الصورة) --}}
    <div class="bg-white shadow-md rounded-lg p-8 mb-10">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-8">Créer une annonce :</h2>

        <form action="{{ route('proprietaire.annoncesproprietaire.store') }}" method="POST" enctype="multipart/form-data" id="form-annonce-proprietaire">
            @csrf

            <div class="grid grid-cols-1 gap-6">

                {{-- ✅ رفع الصور --}}
                <div>
                    <label for="photos_proprietaire" class="block mb-2 font-semibold text-gray-700">Photos (plusieurs possibles)</label>
                    <input type="file" name="photos[]" id="photos_proprietaire" multiple accept="image/jpeg,image/png,image/jpg" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 file:bg-blue-600 file:text-white file:font-semibold file:px-4 file:py-2 file:rounded file:border-0 file:cursor-pointer">
                    @error('photos.*')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('photos')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label for="titre_anno_proprietaire" class="block mb-2 font-semibold text-gray-700">Titre</label>
                    <input type="text" name="titre_anno" id="titre_anno_proprietaire" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                    <label for="prix_log_proprietaire" class="block mb-2 font-semibold text-gray-700">Prix</label>
                    <input type="number" name="prix_log" id="prix_log_proprietaire" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                    <label for="localisation_log_proprietaire" class="block mb-2 font-semibold text-gray-700">localisation</label>
                    <input type="text" name="localisation_log" id="localisation_log_proprietaire" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Équipement</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipements[]" value="Wi-Fi" class="form-checkbox">
                                <span class="ml-2">Wi-Fi</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipements[]" value="Climatisation/Chauffage" class="form-checkbox">
                                <span class="ml-2">Climatisation/Chauffage</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipements[]" value="Télévision" class="form-checkbox">
                                <span class="ml-2">Télévision</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipements[]" value="Machine à laver" class="form-checkbox">
                                <span class="ml-2">Machine à laver</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipements[]" value="Cuisine équipée" class="form-checkbox">
                                <span class="ml-2">Cuisine équipée</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipements[]" value="Réfrigérateur" class="form-checkbox">
                                <span class="ml-2">Réfrigérateur</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipements[]" value="Douche/Baignoire" class="form-checkbox">
                                <span class="ml-2">Douche/Baignoire</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipements[]" value="Parking" class="form-checkbox">
                                <span class="ml-2">Parking</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipements[]" value="Draps et serviettes fournies" class="form-checkbox">
                                <span class="ml-2">Draps et serviettes fournies</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipements[]" value="Balcon/Terrasse" class="form-checkbox">
                                <span class="ml-2">Balcon/Terrasse</span>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex justify-center mt-8">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-8 rounded">
                    Créer le logement
                </button>
            </div>

        </form>
    </div>

    {{-- Liste des annonces --}}
    <h2 class="text-center mb-5" style="font-size: 28px; font-weight: bold; color: #2c3e50;">Liste des annonces</h2>

    <div class="container">
        <div class="row">
            @isset($annonces)
            @forelse ($annonces as $annonce)
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="card h-100 shadow-sm border-0 w-100" style="border-radius: 10px;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title fw-bold fs-4 mb-3">{{ $annonce->titre_anno }}</h5>
                            <p class="mb-1"><strong>💰 Prix:</strong> {{ $annonce->prix_log }} DH/mois</p>
                            <p class="mb-1"><strong>📍 localisation_log:</strong> {{ $annonce->localisation_log }}</p>
                            <p class="mb-1"><strong>🟢 Statut:</strong> En ligne</p>
                            @if($annonce->date_publication_anno)
                            <p class="mb-1"><strong>🗓 Publiée le:</strong> {{ \Carbon\Carbon::parse($annonce->date_publication_anno)->format('d F Y') }}</p>
                            @endif
                            @if($annonce->nombre_colocataires)
                            <p class="mb-1"><strong>👥 Nombre de colocataires:</strong> {{ $annonce->nombre_colocataires }}</p>
                            @endif
                            @if($annonce->equipements)
                            <p class="mb-1"><strong>🛠 Équipement:</strong> {{ implode(', ', json_decode($annonce->equipements, true)) }}</p>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('proprietaire.editannonce', $annonce->id) }}" class="btn text-dark fw-bold px-3" style="background-color: #EBDFD5;">
                                Modifier
                            </a>
                            <form action="{{ route('proprietaire.annoncesproprietaire.destroy', $annonce->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn text-dark fw-bold px-3" style="background-color: #EBDFD5;">
                                    Supprimer
                                </button>
                            </form>
                            <a href="#" class="btn text-dark fw-bold px-3" style="background-color: #EBDFD5;">
                                Gérer demandes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-muted">Aucune annonce disponible.</p>
            @endforelse
            @endisset
        </div>

        {{-- Pagination --}}
        <div class="mt-5">
            {{ $annonces->links() }}
        </div>
    </div>

</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('form-annonce-proprietaire');

        if (!form) {
            console.error('Formulaire #form-annonce-proprietaire introuvable');
            return;
        }

        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            const formData = new FormData(form);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

            try {
                const response = await axios.post("{{ route('proprietaire.annoncesproprietaire.store') }}", formData, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const json = response.data;

                if (json.success) {
                    const equipementsList = json.annonce.equipements ? JSON.parse(json.annonce.equipements).join(', ') : '';
                    const newCard = `
                        <div class="col-md-4 mb-4 d-flex align-items-stretch">
                            <div class="card h-100 shadow-sm border-0 w-100" style="border-radius: 10px;">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <h5 class="card-title fw-bold fs-4 mb-3">${json.annonce.titre_anno}</h5>
                                        <p class="mb-1"><strong>💰 Prix:</strong> ${json.annonce.prix_log} DH/mois</p>
                                        <p class="mb-1"><strong>📍 localisation_log:</strong> ${json.annonce.localisation_log}</p>
                                        <p class="mb-1"><strong>🟢 Statut:</strong> En ligne</p>
                                        ${json.annonce.date_publication_anno ? `<p class="mb-1"><strong>🗓 Publiée le:</strong> ${new Date(json.annonce.date_publication_anno).toLocaleDateString('fr-FR')}</p>` : ''}
                                        ${json.annonce.nombre_colocataires ? `<p class="mb-1"><strong>👥 Nombre de colocataires:</strong> ${json.annonce.nombre_colocataires}</p>` : ''}
                                        ${equipementsList ? `<p class="mb-1"><strong>🛠 Équipement:</strong> ${equipementsList}</p>` : ''}
                                    </div>
                                    <div class="d-flex justify-content-between mt-4">
                                        <a href="{{ route('proprietaire.modifierannonceproprietaire', ':id') }}".replace(':id', json.annonce.id)" class="btn text-dark fw-bold px-3" style="background-color: #EBDFD5;">
                                            Modifier
                                        </a>
                                        <form action="{{ route('proprietaire.annoncesproprietaire.destroy', ':id') }}".replace(':id', json.annonce.id)" method="POST" onsubmit="return confirm('Confirmer la suppression?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn text-dark fw-bold px-3" style="background-color: #EBDFD5;">
                                                Supprimer
                                            </button>
                                        </form>
                                        <a href="#" class="btn text-dark fw-bold px-3" style="background-color: #EBDFD5;">
                                            Gérer demandes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    const annoncesContainer = document.querySelector('.row');
                    if (annoncesContainer) {
                        annoncesContainer.insertAdjacentHTML('afterbegin', newCard);
                    } else {
                        console.error('Conteneur .row introuvable');
                    }

                    form.reset();
                    alert(json.message || 'Annonce créée avec succès !');
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