<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">🏆 <?= esc($treasure['name']) ?></h1>
            <p class="text-gray-600">Leaderboard - Best Attempts</p>
            
            <div class="mt-4 flex justify-center space-x-6 text-sm">
                <div class="text-center">
                    <div class="font-bold text-blue-600"><?= $treasure['points'] ?></div>
                    <div class="text-gray-500">Max Points</div>
                </div>
                <div class="text-center">
                    <div class="font-bold text-green-600"><?= $treasure['radius'] ?>m</div>
                    <div class="text-gray-500">Target Radius</div>
                </div>
                <div class="text-center">
                    <div class="font-bold text-blue-600">🎯 Available</div>
                    <div class="text-gray-500">Status</div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($leaderboard)): ?>
            <div class="space-y-3">
                <?php foreach ($leaderboard as $index => $attempt): ?>
                    <div class="flex items-center justify-between p-4 <?= $index === 0 ? 'bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200' : 'bg-gray-50' ?> rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="text-2xl font-bold <?= $index === 0 ? 'text-yellow-600' : ($index === 1 ? 'text-gray-500' : ($index === 2 ? 'text-orange-600' : 'text-gray-400')) ?>">
                                <?php if ($index === 0): ?>
                                    🥇
                                <?php elseif ($index === 1): ?>
                                    🥈
                                <?php elseif ($index === 2): ?>
                                    🥉
                                <?php else: ?>
                                    <?= $index + 1 ?>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="font-semibold text-lg">
                                    <?php if (!empty($attempt['username'])): ?>
                                        <?= esc($attempt['username']) ?>
                                        <span class="text-xs text-green-600">👤</span>
                                    <?php elseif (!empty($attempt['full_name'])): ?>
                                        <?= esc($attempt['full_name']) ?>
                                    <?php else: ?>
                                        <?= esc($attempt['player_name']) ?>
                                    <?php endif; ?>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <?= date('M j, Y H:i', strtotime($attempt['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-green-600"><?= round($attempt['best_distance'] ?? $attempt['distance']) ?>m</div>
                            <div class="text-sm text-blue-600 font-medium"><?= $attempt['points_earned'] ?> points</div>
                            <?php if ($attempt['is_success']): ?>
                                <div class="text-xs text-green-600 font-bold">🏆 FOUND!</div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-6 text-center text-sm text-gray-500">
                Showing best attempts from <?= count($leaderboard) ?> player<?= count($leaderboard) !== 1 ? 's' : '' ?>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <div class="text-6xl mb-4">🗺️</div>
                <p class="text-gray-500 text-lg">No attempts yet for this treasure!</p>
                <p class="text-sm text-gray-400 mt-2">Be the first to hunt for <?= esc($treasure['name']) ?>.</p>
                
                <?php if (session()->get('is_logged_in')): ?>
                    <div class="mt-6">
                        <a href="/game" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition duration-200">
                            🎮 Start Hunting Now!
                        </a>
                    </div>
                <?php else: ?>
                    <div class="mt-6 space-x-4">
                        <a href="/auth/register" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition duration-200">
                            📝 Register to Hunt
                        </a>
                        <a href="/auth/login" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition duration-200">
                            🔑 Login
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="mt-6 text-center space-x-4">
        <a href="/game/treasures" class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition duration-200">
            🏴‍☠️ All Treasures
        </a>
        <?php if ($treasure['is_active'] && session()->get('is_logged_in')): ?>
            <a href="/game" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition duration-200">
                🎮 Hunt This Treasure
            </a>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
