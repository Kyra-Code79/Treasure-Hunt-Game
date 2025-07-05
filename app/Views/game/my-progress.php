<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold mb-8 text-center">📊 My Treasure Hunting Progress</h1>
    
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-50 p-6 rounded-lg text-center">
            <div class="text-3xl font-bold text-blue-600"><?= $stats['treasures_completed'] ?></div>
            <div class="text-sm text-blue-800">Treasures Found</div>
        </div>
        <div class="bg-green-50 p-6 rounded-lg text-center">
            <div class="text-3xl font-bold text-green-600"><?= $stats['total_treasures_attempted'] ?></div>
            <div class="text-sm text-green-800">Treasures Attempted</div>
        </div>
        <div class="bg-purple-50 p-6 rounded-lg text-center">
            <div class="text-3xl font-bold text-purple-600"><?= $stats['total_attempts'] ?></div>
            <div class="text-sm text-purple-800">Total Attempts</div>
        </div>
        <div class="bg-yellow-50 p-6 rounded-lg text-center">
            <div class="text-3xl font-bold text-yellow-600"><?= $stats['total_best_points'] ?></div>
            <div class="text-sm text-yellow-800">Total Best Points</div>
        </div>
    </div>
    
    <!-- Progress Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($treasures as $index => $treasure): ?>
            <div class="bg-white rounded-lg shadow-lg p-6 <?= $treasure['user_progress'] && $treasure['user_progress']['is_completed'] ? 'ring-2 ring-green-500' : '' ?>">
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
                    </div>
                </div>
                
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Max Points:</span>
                        <span class="font-medium"><?= $treasure['points'] ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Target Radius:</span>
                        <span class="font-medium"><?= $treasure['radius'] ?>m</span>
                    </div>
                    
                    <?php if ($treasure['user_progress']): ?>
                        <hr class="my-3">
                        <div class="bg-blue-50 p-3 rounded">
                            <div class="text-blue-800 font-medium mb-2">Your Progress:</div>
                            <div class="space-y-1 text-xs">
                                <div class="flex justify-between">
                                    <span>Attempts:</span>
                                    <span><?= $treasure['user_progress']['total_attempts'] ?></span>
                                </div>
                                <?php if ($treasure['user_progress']['best_distance']): ?>
                                    <div class="flex justify-between">
                                        <span>Best Distance:</span>
                                        <span><?= round($treasure['user_progress']['best_distance']) ?>m</span>
                                    </div>
                                <?php endif; ?>
                                <div class="flex justify-between">
                                    <span>Best Points:</span>
                                    <span><?= $treasure['user_progress']['best_points'] ?></span>
                                </div>
                                <?php if ($treasure['user_progress']['is_completed']): ?>
                                    <div class="flex justify-between text-green-600">
                                        <span>Completed:</span>
                                        <span><?= date('M j, Y', strtotime($treasure['user_progress']['completed_at'])) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="mt-4 flex space-x-2">
                    <a href="/game/treasure/<?= $treasure['id'] ?>/leaderboard" 
                       class="flex-1 text-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm rounded transition duration-200">
                        📊 Leaderboard
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="mt-8 text-center">
        <a href="/game" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition duration-200">
            🎮 Continue Hunting
        </a>
    </div>
</div>
<?= $this->endSection() ?>
