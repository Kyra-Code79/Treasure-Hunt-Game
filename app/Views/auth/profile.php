<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">👤 My Profile</h1>
            <p class="text-gray-600">Your treasure hunting statistics and account information</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Profile Info -->
            <div>
                <h2 class="text-xl font-bold mb-4">Account Information</h2>
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/auth/profile/update">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100" 
                               value="<?= esc($user['username']) ?>" readonly>
                        <p class="text-xs text-gray-500 mt-1">Username cannot be changed</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="full_name">Full Name</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                               id="full_name" name="full_name" type="text" value="<?= esc($user['full_name']) ?>" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                               id="email" name="email" type="email" value="<?= esc($user['email']) ?>" required>
                    </div>
                    
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                            type="submit">
                        Update Profile
                    </button>
                </form>
            </div>
            
            <!-- Statistics -->
            <div>
                <h2 class="text-xl font-bold mb-4">Treasure Hunting Stats</h2>
                
                <div class="space-y-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600"><?= number_format($user['total_score']) ?></div>
                        <div class="text-sm text-blue-800">Total Points</div>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-green-600"><?= $user['treasures_found'] ?></div>
                        <div class="text-sm text-green-800">Treasures Found</div>
                    </div>
                    
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600"><?= $user['games_played'] ?></div>
                        <div class="text-sm text-purple-800">Games Played</div>
                    </div>
                    
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">
                            <?= $user['games_played'] > 0 ? round($user['total_score'] / $user['games_played']) : 0 ?>
                        </div>
                        <div class="text-sm text-yellow-800">Average Points per Game</div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <p class="text-sm text-gray-600">Member since: <?= date('F j, Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6 text-center">
        <a href="/game" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition duration-200">
            🎮 Back to Game
        </a>
    </div>
</div>
<?= $this->endSection() ?>
