<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="text-center py-8 md:py-12">
    <div class="max-w-6xl mx-auto px-4">
        <h1 class="text-4xl md:text-6xl font-bold text-gray-800 mb-4 md:mb-6">🏴‍☠️ Treasure Hunt Indonesia</h1>
        <p class="text-lg md:text-xl text-gray-600 mb-6 md:mb-8 px-4">
            Welcome to the ultimate treasure hunting adventure across Indonesia! Register to start hunting for hidden treasures in the beautiful archipelago.
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 mb-8 md:mb-12">
            <div class="bg-white rounded-lg shadow-lg p-4 md:p-6">
                <div class="text-3xl md:text-4xl mb-3 md:mb-4">🗺️</div>
                <h3 class="text-lg md:text-xl font-bold mb-2">Indonesian Map</h3>
                <p class="text-sm md:text-base text-gray-600">Explore Indonesia's 17,000+ islands and find treasures hidden across the archipelago.</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg p-4 md:p-6">
                <div class="text-3xl md:text-4xl mb-3 md:mb-4">🎯</div>
                <h3 class="text-lg md:text-xl font-bold mb-2">Precision Scoring</h3>
                <p class="text-sm md:text-base text-gray-600">Get more points for guesses closer to the treasure using the Haversine formula.</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg p-4 md:p-6">
                <div class="text-3xl md:text-4xl mb-3 md:mb-4">🏆</div>
                <h3 class="text-lg md:text-xl font-bold mb-2">Competitive Play</h3>
                <p class="text-sm md:text-base text-gray-600">Compete with registered players and climb to the top of the leaderboard.</p>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-6 md:mb-8">
            <?php if (session()->get('is_logged_in')): ?>
                <a href="/game" class="w-full sm:w-auto inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-base md:text-lg rounded-lg transition duration-200">
                    🎮 Start Hunting
                </a>
            <?php else: ?>
                <a href="/auth/register" class="w-full sm:w-auto inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-base md:text-lg rounded-lg transition duration-200">
                    🎮 Register & Start Hunting
                </a>
                <a href="/auth/login" class="w-full sm:w-auto inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 bg-green-600 hover:bg-green-700 text-white font-bold text-base md:text-lg rounded-lg transition duration-200">
                    🔑 Login to Play
                </a>
            <?php endif; ?>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="/game/leaderboard" class="w-full sm:w-auto inline-flex items-center justify-center px-4 md:px-6 py-2 md:py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition duration-200">
                🏆 View Leaderboard
            </a>
            <a href="/game/treasures" class="w-full sm:w-auto inline-flex items-center justify-center px-4 md:px-6 py-2 md:py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition duration-200">
                🗺️ Browse Treasures
            </a>
        </div>
    </div>
</div>

<?php if (!session()->get('is_logged_in')): ?>
<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 md:p-6 mt-6 md:mt-8 mx-4">
    <div class="text-center">
        <h2 class="text-xl md:text-2xl font-bold text-yellow-800 mb-3 md:mb-4">🔐 Ready to Join the Hunt?</h2>
        <p class="text-sm md:text-base text-yellow-700 mb-3 md:mb-4">
            To play the treasure hunting game, you need to create a free account. This allows us to track your progress, 
            maintain leaderboards, and ensure fair competition among treasure hunters!
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/auth/register" class="w-full sm:w-auto inline-flex items-center justify-center px-4 md:px-6 py-2 md:py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-bold rounded-lg transition duration-200">
                📝 Create Free Account
            </a>
            <a href="/auth/login" class="w-full sm:w-auto inline-flex items-center justify-center px-4 md:px-6 py-2 md:py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-lg transition duration-200">
                🔑 Already Have Account?
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow-lg p-6 md:p-8 mt-8 md:mt-12 mx-4">
    <h2 class="text-2xl md:text-3xl font-bold text-center mb-6 md:mb-8">How to Play - Cara Bermain</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="text-center">
            <div class="bg-blue-100 rounded-full w-12 h-12 md:w-16 md:h-16 flex items-center justify-center mx-auto mb-3 md:mb-4">
                <span class="text-lg md:text-2xl font-bold text-blue-600">1</span>
            </div>
            <h3 class="font-bold mb-2 text-sm md:text-base">Register Account</h3>
            <p class="text-xs md:text-sm text-gray-600">Create your free treasure hunter account to start playing and compete with others.</p>
        </div>
        
        <div class="text-center">
            <div class="bg-green-100 rounded-full w-12 h-12 md:w-16 md:h-16 flex items-center justify-center mx-auto mb-3 md:mb-4">
                <span class="text-lg md:text-2xl font-bold text-green-600">2</span>
            </div>
            <h3 class="font-bold mb-2 text-sm md:text-base">Klik Peta Indonesia</h3>
            <p class="text-xs md:text-sm text-gray-600">Click anywhere on the Indonesian map to make your treasure guess.</p>
        </div>
        
        <div class="text-center">
            <div class="bg-yellow-100 rounded-full w-12 h-12 md:w-16 md:h-16 flex items-center justify-center mx-auto mb-3 md:mb-4">
                <span class="text-lg md:text-2xl font-bold text-yellow-600">3</span>
            </div>
            <h3 class="font-bold mb-2 text-sm md:text-base">Dapatkan Feedback</h3>
            <p class="text-xs md:text-sm text-gray-600">See how close you were to Indonesian landmarks and earn points.</p>
        </div>
        
        <div class="text-center">
            <div class="bg-purple-100 rounded-full w-12 h-12 md:w-16 md:h-16 flex items-center justify-center mx-auto mb-3 md:mb-4">
                <span class="text-lg md:text-2xl font-bold text-purple-600">4</span>
            </div>
            <h3 class="font-bold mb-2 text-sm md:text-base">Naik Peringkat</h3>
            <p class="text-xs md:text-sm text-gray-600">Keep exploring Indonesia to improve your score and reach the top!</p>
        </div>
    </div>
</div>

<div class="bg-gray-50 rounded-lg p-6 md:p-8 mt-6 md:mt-8 mx-4">
    <h2 class="text-xl md:text-2xl font-bold text-center mb-4 md:mb-6">🌟 Explore Without Playing</h2>
    <p class="text-center text-gray-600 mb-4 md:mb-6 text-sm md:text-base">
        Want to see what treasures are available and check out the competition? You can browse everything without registering!
    </p>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
        <div class="text-center">
            <a href="/game/treasures" class="block bg-white hover:bg-gray-50 rounded-lg p-4 md:p-6 shadow transition duration-200">
                <div class="text-2xl md:text-3xl mb-2">🗺️</div>
                <h3 class="font-bold mb-2 text-sm md:text-base">Browse All Treasures</h3>
                <p class="text-xs md:text-sm text-gray-600">See all Indonesian treasure locations, their status, and who found them.</p>
            </a>
        </div>
        <div class="text-center">
            <a href="/game/leaderboard" class="block bg-white hover:bg-gray-50 rounded-lg p-4 md:p-6 shadow transition duration-200">
                <div class="text-2xl md:text-3xl mb-2">🏆</div>
                <h3 class="font-bold mb-2 text-sm md:text-base">View Leaderboards</h3>
                <p class="text-xs md:text-sm text-gray-600">Check out the top treasure hunters and recent game activity.</p>
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
