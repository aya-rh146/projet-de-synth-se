@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10">
    <div class="bg-white shadow-md rounded-lg p-8 mb-10">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-8">Modifier l'annonce : {{ $annonce->titre_anno }}</h2>

        <form id="edit-annonce-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="annonce_id" value="{{ $annonce->id }}">

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="photos_proprietaire" class="block mb-2 font-semibold text-gray-700">Photos :</label>
                    <input type="file" name="photos" id="photos_proprietaire" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" accept="image/jpeg,image/png,image/jpg">
                    @if($annonce->photos)
                        <img src="{{ asset('storage/' . $annonce->photos) }}" alt="Photo actuelle" class="w-32 h-32 object-cover mt-2" style="border-radius: 10px;">
                    @endif
                </div>

                <div>
                    <label for="titre_anno_proprietaire" class="block mb-2 font-semibold text-gray-700">Titre</label>
                    <input type="text" name="titre_anno" id="titre_anno_proprietaire" value="{{ old('titre_anno', $annonce->titre_anno) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                    <label for="budget_proprietaire" class="block mb-2 font-semibold text-gray-700">Prix</label>
                    <input type="number" name="budget" id="budget_proprietaire" value="{{ old('budget', $annonce->budget) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                    <label for="localisation_proprietaire" class="block mb-2 font-semibold text-gray-700">Localisation</label>
                    <input type="text" name="localisation" id="localisation_proprietaire" value="{{ old('localisation', $annonce->localisation) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Équipement</label>
                    <div class="grid grid-cols-2 gap-2">
                        @php
                            $equipements = json_decode($annonce->equipement, true) ?? [];
                        @endphp
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipement[]" value="Wi-Fi" {{ in_array('Wi-Fi', $equipements) ? 'checked' : '' }} class="form-checkbox">
                                <span class="ml-2">Wi-Fi</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipement[]" value="Climatisation/Chauffage" {{ in_array('Climatisation/Chauffage', $equipements) ? 'checked' : '' }} class="form-checkbox">
                                <span class="ml-2">Climatisation/Chauffage</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipement[]" value="Télévision" {{ in_array('Télévision', $equipements) ? 'checked' : '' }} class="form-checkbox">
                                <span class="ml-2">Télévision</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipement[]" value="Machine à laver" {{ in_array('Machine à laver', $equipements) ? 'checked' : '' }} class="form-checkbox">
                                <span class="ml-2">Machine à laver</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipement[]" value="Cuisine équipée" {{ in_array('Cuisine équipée', $equipements) ? 'checked' : '' }} class="form-checkbox">
                                <span class="ml-2">Cuisine équipée</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipement[]" value="Réfrigérateur" {{ in_array('Réfrigérateur', $equipements) ? 'checked' : '' }} class="form-checkbox">
                                <span class="ml-2">Réfrigérateur</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipement[]" value="Douche/Baignoire" {{ in_array('Douche/Baignoire', $equipements) ? 'checked' : '' }} class="form-checkbox">
                                <span class="ml-2">Douche/Baignoire</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipement[]" value="Parking" {{ in_array('Parking', $equipements) ? 'checked' : '' }} class="form-checkbox">
                                <span class="ml-2">Parking</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipement[]" value="Draps et serviettes fournies" {{ in_array('Draps et serviettes fournies', $equipements) ? 'checked' : '' }} class="form-checkbox">
                                <span class="ml-2">Draps et serviettes fournies</span>
                            </label>
                        </div>
                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="equipement[]" value="Balcon/Terrasse" {{ in_array('Balcon/Terrasse', $equipements) ? 'checked' : '' }} class="form-checkbox">
                                <span class="ml-2">Balcon/Terrasse</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-8">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded">
                    Mettre à jour l'annonce
                </button>
                <a href="{{ route('proprietaire.annoncesproprietaire.index') }}" class="ml-4 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-8 rounded text-center inline-block">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.getElementById('edit-annonce-form').addEventListener('submit', async function(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await axios.put(`/proprietaire/annonces/${form.querySelector('input[name="annonce_id"]').value}`, formData, {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'multipart/form-data',
                }
            });

            const json = response.data;

            if (json.success) {
                alert(json.message);
                window.location.href = '{{ route('proprietaire.annoncesproprietaire.index') }}';
            } else {
                alert('Erreur: ' + json.message);
            }

        } catch (error) {
            console.error('Erreur:', error);
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
</script>
@endpush