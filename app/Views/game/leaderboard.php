<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-blue-800">🏆 Global Treasure Hunt Leaderboard</h2>
                <p class="text-blue-600 text-sm">Top treasure hunters across Indonesia</p>
            </div>
            <?php if (!session()->get('is_logged_in')): ?>
                <div class="text-right">
                    <p class="text-blue-700 text-sm mb-2">Want to compete?</p>
                    <a href="/auth/register" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium">
                        Join the Hunt!
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Leaderboard -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6 flex items-center">
            🏆 Top Treasure Hunters
        </h2>
        
        <?php if (!empty($leaderboard)): ?>
            <div class="space-y-3">
                <?php foreach ($leaderboard as $index => $player): ?>
                    <div class="flex items-center justify-between p-4 <?= $index < 3 ? 'bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200' : 'bg-gray-50' ?> rounded-lg">
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
                                <div class="font-semibold text-lg flex items-center space-x-2">
                                    <span><?= $player['username'] ? esc($player['username']) : esc($player['name']) ?></span>
                                    <?php if ($player['username']): ?>
                                        <span class="text-green-600 text-sm">👤</span>
                                    <?php else: ?>
                                        <span class="text-gray-400 text-sm">👻</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-sm text-gray-600"><?= $player['games_played'] ?> games played</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-blue-600"><?= number_format($player['total_score']) ?></div>
                            <div class="text-sm text-gray-500">points</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <div class="text-6xl mb-4">🏴‍☠️</div>
                <p class="text-gray-500">No treasure hunters yet!</p>
                <p class="text-sm text-gray-400 mt-2">Be the first to find the treasure.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recent Attempts -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6 flex items-center">
            📊 Recent Game Activity
        </h2>
        
        <?php if (!empty($recentAttempts)): ?>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                <?php foreach ($recentAttempts as $attempt): ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="text-lg">
                                <?= $attempt['is_success'] ? '🎉' : '❌' ?>
                            </div>
                            <div>
                                <div class="font-medium"><?= esc($attempt['player_name']) ?></div>
                                <div class="text-sm text-gray-600">
                                    <?= date('M j, H:i', strtotime($attempt['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <?php if ($attempt['is_success']): ?>
                                <div class="text-green-600 font-bold">+<?= $attempt['points_earned'] ?></div>
                            <?php endif; ?>
                            <div class="text-sm text-gray-500"><?= round($attempt['distance']) ?>m</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <div class="text-4xl mb-4">🔍</div>
                <p class="text-gray-500">No attempts yet!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="mt-6 text-center space-x-4">
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
    <a href="/game/treasures" class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition duration-200">
        🗺️ View Treasures
    </a>
</div>
<?= $this->endSection() ?>
