@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10">
    {{-- Message succès ou erreur --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5">
            {{ session('error') }}
        </div>
    @endif

    {{-- Formulaire modification --}}
    <div class="bg-white shadow-md rounded-lg p-8 mb-10">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-8">Modifier l'annonce : {{ $annonce->titre_anno }}</h2>

        <form id="edit-annonce-form" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Méthode PUT pour la mise à jour -->
            <input type="hidden" name="annonce_id" value="{{ $annonce->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="titre_anno" class="block mb-2 font-semibold text-gray-700">Titre</label>
                    <input type="text" name="titre_anno" id="titre_anno" value="{{ old('titre_anno', $annonce->titre_anno) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                    @error('titre_anno')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="type_log" class="block mb-2 font-semibold text-gray-700">Type de logement</label>
                    <select name="type_log" id="type_log" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                        <option value="studio" {{ old('type_log', $annonce->logement->type_log) == 'studio' ? 'selected' : '' }}>Studio</option>
                        <option value="appartement" {{ old('type_log', $annonce->logement->type_log) == 'appartement' ? 'selected' : '' }}>Appartement</option>
                        <option value="maison" {{ old('type_log', $annonce->logement->type_log) == 'maison' ? 'selected' : '' }}>Maison</option>
                    </select>
                    @error('type_log')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="prix_log" class="block mb-2 font-semibold text-gray-700">Budget (MAD/mois)</label>
                    <input type="number" name="prix_log" id="prix_log" value="{{ old('prix_log', $annonce->logement->prix_log) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                    @error('prix_log')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="ville" class="block mb-2 font-semibold text-gray-700">Ville</label>
                    <input type="text" name="ville" id="ville" value="{{ old('ville', $annonce->logement->ville) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                    @error('ville')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="nombre_colocataire_log" class="block mb-2 font-semibold text-gray-700">Nombre de colocataires</label>
                    <input type="number" name="nombre_colocataire_log" id="nombre_colocataire_log" value="{{ old('nombre_colocataire_log', $annonce->logement->nombre_colocataire_log) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                    @error('nombre_colocataire_log')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="localisation_log" class="block mb-2 font-semibold text-gray-700">Localisation</label>
                    <input type="text" name="localisation_log" id="localisation_log" value="{{ old('localisation_log', $annonce->logement->localisation_log) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                    @error('localisation_log')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mt-6">
                <label for="description_anno" class="block mb-2 font-semibold text-gray-700">Description</label>
                <textarea name="description_anno" id="description_anno" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>{{ old('description_anno', $annonce->description_anno) }}</textarea>
                @error('description_anno')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-center mt-8">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded-full">
                    Mettre à jour l'annonce
                </button>
                <a href="{{ route('locataire.annonceslocataire') }}" class="ml-4 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-8 rounded-full text-center inline-block">
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
document.getElementById('edit-annonce-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    axios.put(`/locataire/annonceslocataire/${form.querySelector('input[name="annonce_id"]').value}`, formData, {
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'multipart/form-data',
        }
    })
    .then(response => {
        console.log(response.data);
        const json = response.data;
        alert('Annonce mise à jour avec succès.');
        window.location.href = '{{ route('locataire.annonceslocataire') }}';
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (error.response && error.response.data.errors) {
            let errorMessage = 'Erreurs de validation:\n';
            for (let field in error.response.data.errors) {
                errorMessage += `${field}: ${error.response.data.errors[field][0]}\n`;
            }
            alert(errorMessage);
        } else {
            alert('Erreur lors de la mise à jour: ' + (error.response?.data?.message || error.message));
        }
    });
});
</script>
@endpush