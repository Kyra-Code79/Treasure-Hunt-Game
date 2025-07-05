<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-purple-800">🗺️ Indonesian Treasure Locations</h2>
                <p class="text-purple-600 text-sm">Explore all treasure locations across the archipelago</p>
            </div>
            <?php if (!session()->get('is_logged_in')): ?>
                <div class="text-right">
                    <p class="text-purple-700 text-sm mb-2">Ready to hunt?</p>
                    <a href="/auth/register" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm font-medium">
                        Start Playing!
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold mb-8 text-center">🏴‍☠️ All Indonesian Treasures</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($treasures as $index => $treasure): ?>
            <div class="bg-white rounded-lg shadow-lg p-6 <?= $treasure['is_active'] ? 'ring-2 ring-blue-500' : '' ?>">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold"><?= esc($treasure['name']) ?></h3>
                    <div class="flex space-x-2">
                        <?php if ($treasure['user_progress'] && $treasure['user_progress']['is_completed']): ?>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">✅ Completed</span>
                        <?php elseif ($treasure['user_progress']): ?>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">🎯 In Progress</span>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">⏳ Not Started</span>
                        <?php endif; ?>
                        
                        <?php if ($treasure['is_active']): ?>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">🎯 Available</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Points:</span>
                        <span class="font-medium"><?= $treasure['points'] ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Radius:</span>
                        <span class="font-medium"><?= $treasure['radius'] ?>m</span>
                    </div>
                    
                    <?php if ($treasure['user_progress'] && $treasure['user_progress']['is_completed']): ?>
                        <div class="mt-4 p-3 bg-green-50 rounded-lg">
                            <div class="text-green-800 font-medium">✅ You completed this!</div>
                            <div class="text-xs text-green-600 mt-1">
                                Your best: <?= round($treasure['user_progress']['best_distance']) ?>m<br>
                                Completed: <?= date('M j, Y', strtotime($treasure['user_progress']['completed_at'])) ?>
                            </div>
                        </div>
                    <?php elseif ($treasure['user_progress']): ?>
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <div class="text-blue-800 font-medium">🎯 Your Progress:</div>
                            <div class="text-xs text-blue-600 mt-1">
                                Attempts: <?= $treasure['user_progress']['total_attempts'] ?><br>
                                <?php if ($treasure['user_progress']['best_distance']): ?>
                                    Best distance: <?= round($treasure['user_progress']['best_distance']) ?>m<br>
                                <?php endif; ?>
                                Best points: <?= $treasure['user_progress']['best_points'] ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="mt-4 flex space-x-2">
                    <a href="/game/treasure/<?= $treasure['id'] ?>/leaderboard" 
                       class="flex-1 text-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm rounded transition duration-200">
                        📊 Leaderboard
                    </a>
                    <?php if ($treasure['is_active'] && session()->get('is_logged_in')): ?>
                        <a href="/game" 
                           class="flex-1 text-center px-3 py-2 bg-green-100 hover:bg-green-200 text-green-800 text-sm rounded transition duration-200">
                            🎮 Hunt Now
                        </a>
                    <?php elseif ($treasure['is_active'] && !session()->get('is_logged_in')): ?>
                        <a href="/auth/register" 
                           class="flex-1 text-center px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 text-sm rounded transition duration-200">
                            🔑 Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="mt-8 text-center space-x-4">
        <?php if (session()->get('is_logged_in')): ?>
            <a href="/game" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition duration-200">
                🎮 Play Game
            </a>
        <?php else: ?>
            <a href="/auth/register" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition duration-200">
                🎮 Register to Play
            </a>
            <a href="/auth/login" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition duration-200">
                🔑 Login
            </a>
        <?php endif; ?>
        <a href="/game/leaderboard" class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition duration-200">
            🏆 Leaderboard
        </a>
    </div>
</div>
<?= $this->endSection() ?>
