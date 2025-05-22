@extends('layouts.app')

@section('content')

<style>
    .faq-question {
        color: #244F76;
        font-size: 18px;
        font-weight: 600;
        padding: 8px 0;
        transition: all 0.3s ease;
    }
    .faq-question.active {
        font-weight: 700;
        border-left: 4px solid #244F76;
        padding-left: 12px;
    }
    .faq-answer {
        display: none;
        color: #4A4A4A;
        padding: 12px;
        border-radius: 5px;
        font-size: 16px;
        line-height: 1.5;
        margin: 0 0 16px 16px;
        transition: all 0.3s ease;
    }
</style>

<!-- Activité de vos réservations -->
<section class="container mx-auto mb-16">
    <h2 class="text-4xl font-bold mb-4">Accueil {{ Auth::user()->name ?? 'Ahmed Bekaoui' }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-500 mb-2">Nombre de mes réservations</p>
            <p class="text-xl font-bold">25 demandes</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-500 mb-2">Réservations confirmées</p>
            <p class="text-xl font-bold">12 Appartements</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-500 mb-2">Réservations refusées</p>
            <p class="text-xl font-bold">8 Appartements</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-500 mb-2">Réservations terminées</p>
            <p class="text-xl font-bold">128 messages</p>
        </div>
    </div>
</section>

<!-- Les dernières logements ajoutés -->
<section class="container mx-auto mb-16">
    <h2 class="text-2xl font-semibold mb-6">Les dernières logements ajoutés</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition">
            <img src="{{ asset('images/house1.jpg') }}" alt="Maison" class="w-full h-48 object-cover">
            <div class="p-4">
                <h3 class="font-semibold text-lg mb-2">Maison indépendante</h3>
                <p class="text-green-600 font-bold">1100 DH / MOIS</p>
                <p class="text-sm text-gray-500">Bni Bouayach</p>
                <div class="mt-2 flex items-center">
                    <span class="text-yellow-400">★★★★★</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="container mx-auto mb-16">
    <h2 class="text-2xl font-semibold mb-6 text-center">FAQ</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="italic">"Service exceptionnel et rapide !"</p>
            <p class="mt-4 font-bold">Cassie Williamson</p>
        </div>
    </div>
    <div class="text-center space-y-4">
        <div class="faq-item">
            <p class="faq-question font-semibold mb-2 cursor-pointer text-gray-800 hover:text-blue-600" onclick="toggleAnswer(this)">Qu'est-ce que FindStay ?</p>
            <p class="faq-answer text-gray-600 mb-4 mx-auto max-w-2xl">FindStay est une plateforme qui vous aide à trouver facilement des hébergements de qualité...</p>
        </div>
        <div class="faq-item">
            <p class="faq-question font-semibold mb-2 cursor-pointer text-gray-800 hover:text-blue-600" onclick="toggleAnswer(this)">Comment créer un compte sur FindStay ?</p>
            <p class="faq-answer text-gray-600 mb-4 mx-auto max-w-2xl">Pour créer un compte, cliquez sur 'S'inscrire' et remplissez le formulaire...</p>
        </div>
        <div class="faq-item">
            <p class="faq-question font-semibold mb-2 cursor-pointer text-gray-800 hover:text-blue-600" onclick="toggleAnswer(this)">Comment modifier ou annuler une réservation ?</p>
            <p class="faq-answer text-gray-600 mb-4 mx-auto max-w-2xl">Vous pouvez modifier ou annuler une réservation depuis votre profil...</p>
        </div>
        <div class="faq-item">
            <p class="faq-question font-semibold mb-2 cursor-pointer text-gray-800 hover:text-blue-600" onclick="toggleAnswer(this)">FindStay propose-t-il une application mobile ?</p>
            <p class="faq-answer text-gray-600 mb-4 mx-auto max-w-2xl">Oui, FindStay propose une application mobile disponible sur iOS et Android...</p>
        </div>
    </div>
</section>

<!-- En quelques chiffres -->
<section class="container mx-auto text-center mb-16">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div>
            <p class="text-4xl font-bold">93</p>
            <p class="text-gray-500">Logements gérés au Maroc</p>
        </div>
        <div>
            <p class="text-4xl font-bold">1000</p>
            <p class="text-gray-500">Jeunes loués</p>
        </div>
        <div>
            <p class="text-4xl font-bold">800+</p>
            <p class="text-gray-500">Utilisateurs actifs</p>
        </div>
        <div>
            <p class="text-4xl font-bold">90+</p>
            <p class="text-gray-500">Logements disponibles</p>
        </div>
    </div>
</section>

<script>
    function toggleAnswer(element) {
        const answer = element.nextElementSibling;
        const allAnswers = document.querySelectorAll('.faq-answer');
        const allQuestions = document.querySelectorAll('.faq-question');
        allAnswers.forEach(ans => {
            if (ans !== answer) {
                ans.style.display = 'none';
            }
        });
        allQuestions.forEach(q => {
            q.classList.remove('text-blue-600');
        });
        if (answer.style.display === 'block') {
            answer.style.display = 'none';
            element.classList.remove('text-blue-600');
        } else {
            answer.style.display = 'block';
            element.classList.add('text-blue-600');
        }
    }
</script>
@endsection