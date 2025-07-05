<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Treasure Hunt Game' ?></title>
     <!-- Dynamic favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('icon-thi.png') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map { 
            height: 400px; 
        }
        @media (min-width: 768px) {
            #map { 
                height: 500px; 
            }
        }
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
        }
        /* Mobile menu toggle */
        .mobile-menu {
            display: none;
        }
        .mobile-menu.active {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-blue-600 text-white">
        <div class="container mx-auto px-4">
            <!-- Desktop Navigation -->
            <div class="flex justify-between items-center py-4">
                <a href="/" class="text-xl md:text-2xl font-bold hover:text-blue-200 transition duration-200">
                    🏴‍☠️ <span class="hidden sm:inline">Treasure Hunt Indonesia</span><span class="sm:hidden">TH Indonesia</span>
                </a>
                
                <!-- Mobile menu button -->
                <button class="md:hidden focus:outline-none" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Desktop menu -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/game" class="hover:text-blue-200 transition duration-200 px-2 py-1">Game</a>
                    <a href="/game/leaderboard" class="hover:text-blue-200 transition duration-200 px-2 py-1">Leaderboard</a>
                    <a href="/game/treasures" class="hover:text-blue-200 transition duration-200 px-2 py-1">Treasures</a>
                    
                    <?php if (session()->get('is_logged_in')): ?>
                        <div class="flex items-center space-x-3">
                            <span class="text-sm hidden lg:inline">Welcome, <?= esc(session()->get('full_name')) ?>!</span>
                            <a href="/auth/profile" class="hover:text-blue-200 transition duration-200 px-2 py-1">Profile</a>
                            <a href="/auth/logout" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm transition duration-200">Logout</a>
                        </div>
                    <?php else: ?>
                        <div class="flex space-x-2">
                            <a href="/auth/login" class="bg-green-500 hover:bg-green-600 px-3 py-1 rounded text-sm transition duration-200">Login</a>
                            <a href="/auth/register" class="bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded text-sm transition duration-200">Register</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div id="mobile-menu" class="mobile-menu md:hidden pb-4">
                <div class="flex flex-col space-y-2">
                    <a href="/game" class="hover:text-blue-200 transition duration-200 px-2 py-2 border-b border-blue-500">🎮 Game</a>
                    <a href="/game/leaderboard" class="hover:text-blue-200 transition duration-200 px-2 py-2 border-b border-blue-500">🏆 Leaderboard</a>
                    <a href="/game/treasures" class="hover:text-blue-200 transition duration-200 px-2 py-2 border-b border-blue-500">🗺️ Treasures</a>
                    
                    <?php if (session()->get('is_logged_in')): ?>
                        <div class="px-2 py-2 border-b border-blue-500">
                            <div class="text-sm mb-2">👋 <?= esc(session()->get('full_name')) ?></div>
                            <div class="flex flex-col space-y-2">
                                <a href="/auth/profile" class="hover:text-blue-200 transition duration-200">👤 Profile</a>
                                <a href="/auth/logout" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm transition duration-200 inline-block w-fit">🚪 Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="px-2 py-2 flex flex-col space-y-2">
                            <a href="/auth/login" class="bg-green-500 hover:bg-green-600 px-3 py-2 rounded text-sm transition duration-200 text-center">🔑 Login</a>
                            <a href="/auth/register" class="bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded text-sm transition duration-200 text-center">📝 Register</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mx-4 mt-4">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mx-4 mt-4">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <main class="container mx-auto px-4 py-4">
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="bg-gray-800 text-white p-4 mt-8">
        <div class="container mx-auto text-center px-4">
            <p class="text-sm md:text-base">&copy; 2025 <a href="/" class="text-blue-400 hover:text-blue-300 transition duration-200">Treasure Hunt Indonesia</a>. Explore the beautiful archipelago!</p>
            <p class="text-xs md:text-sm mt-1 text-gray-400">MIT License | Built with CodeIgniter 4 & Leaflet.js</p>
            <p class="text-xs md:text-sm mt-1 text-gray-400">Created By: <a href="https://habibisiregar79.my.id/" class="text-blue-400 hover:text-blue-300 transition duration-200">M Habibi Rizq Zhafar Siregar</a></p>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('active');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const button = event.target.closest('button');
            
            if (!menu.contains(event.target) && !button) {
                menu.classList.remove('active');
            }
        });
    </script>
</body>
</html>
