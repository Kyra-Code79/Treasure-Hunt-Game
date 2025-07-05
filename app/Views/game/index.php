<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 md:gap-6">
    <!-- Game Map -->
    <div class="xl:col-span-2">
        <div class="bg-white rounded-lg shadow-lg p-4 md:p-6">
            <?php if ($currentTreasure): ?>
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-2 sm:space-y-0">
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold mb-1 md:mb-2">🗺️ Find: <?= esc($currentTreasure['name']) ?></h2>
                        <p class="text-sm md:text-base text-gray-600">Your current treasure location in Indonesia</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <?php if ($userProgress): ?>
                            <div class="text-xs md:text-sm text-gray-600">
                                <div>Attempts: <?= $userProgress['total_attempts'] ?></div>
                                <?php if ($userProgress['best_distance']): ?>
                                    <div>Best: <?= round($userProgress['best_distance']) ?>m</div>
                                <?php endif; ?>
                                <?php if ($userProgress['is_completed']): ?>
                                    <div class="text-green-600 font-bold">✅ Completed!</div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <h2 class="text-xl md:text-2xl font-bold mb-2">🏆 All Treasures Completed!</h2>
                <p class="text-sm md:text-base text-gray-600 mb-4">Congratulations! You've found all Indonesian treasures!</p>
            <?php endif; ?>
            
            <div id="map" class="rounded-lg border-2 border-gray-300"></div>
            <div class="mt-3 md:mt-4 text-xs md:text-sm text-gray-600">
                <?php if ($currentTreasure): ?>
                    <p class="mb-1">Click anywhere on the map to guess the treasure location!</p>
                    <p class="text-xs">🎯 <strong>Perfect:</strong> Within 100m | 🔥 <strong>Very Close:</strong> Within 500m | 👍 <strong>Close:</strong> Within 2km</p>
                <?php else: ?>
                    <p>You've completed your treasure hunting journey! Check your <a href="/game/my-progress" class="text-blue-600 hover:underline">progress page</a> to see all your achievements.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Game Info Panel -->
    <div class="space-y-4 md:space-y-6">
        <!-- Player Info -->
        <div class="bg-white rounded-lg shadow-lg p-4 md:p-6">
            <h3 class="text-lg md:text-xl font-bold mb-3 md:mb-4">👤 Player Info</h3>
            <div class="space-y-3">
                <div class="text-center">
                    <div class="text-base md:text-lg font-bold text-green-600">Welcome, <?= esc($currentUser) ?>!</div>
                    <div class="text-xs md:text-sm text-gray-500">Personal treasure hunt</div>
                </div>
                
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-blue-600" id="currentScore">0</div>
                    <div class="text-xs md:text-sm text-gray-500">Session Score</div>
                </div>

                <div class="text-center">
                    <a href="/game/my-progress" class="text-xs md:text-sm text-blue-600 hover:text-blue-800 font-medium">
                        📊 View My Progress →
                    </a>
                </div>
            </div>
        </div>

        <!-- Current Treasure Leaderboard -->
        <?php if ($currentTreasure && !empty($treasureLeaderboard)): ?>
        <div class="bg-white rounded-lg shadow-lg p-4 md:p-6">
            <h3 class="text-lg md:text-xl font-bold mb-3 md:mb-4">🎯 <?= esc($currentTreasure['name']) ?> Champions</h3>
            <div class="space-y-2">
                <?php foreach ($treasureLeaderboard as $index => $progress): ?>
                    <div class="flex justify-between items-center p-2 text-sm <?= $index === 0 ? 'bg-green-50 border border-green-200 rounded' : '' ?>">
                        <div class="flex items-center space-x-2">
                            <span class="font-medium"><?= $index + 1 ?>.</span>
                            <span class="truncate"><?= esc($progress['username'] ?: $progress['full_name']) ?></span>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="font-bold text-green-600"><?= round($progress['best_distance']) ?>m</div>
                            <div class="text-xs text-gray-500"><?= $progress['best_points'] ?> pts</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a href="/game/treasure/<?= $currentTreasure['id'] ?>/leaderboard" class="block text-center mt-3 md:mt-4 text-blue-600 hover:text-blue-800 text-xs md:text-sm font-medium">
                View Full Leaderboard →
            </a>
        </div>
        <?php endif; ?>

        <!-- Game Status -->
        <div class="bg-white rounded-lg shadow-lg p-4 md:p-6">
            <h3 class="text-lg md:text-xl font-bold mb-3 md:mb-4">🎯 Game Status</h3>
            <div id="gameMessage" class="text-center p-3 md:p-4 bg-blue-50 rounded-lg text-blue-800 text-sm md:text-base">
                <?php if ($currentTreasure): ?>
                    Click on the map to start hunting for <?= esc($currentTreasure['name']) ?>!
                <?php else: ?>
                    All treasures completed! 🏆
                <?php endif; ?>
            </div>
            <div id="hintMessage" class="text-center p-2 md:p-3 mt-2 bg-yellow-50 rounded-lg text-yellow-800 text-sm hidden">
                Hint will appear here...
            </div>
            <div class="mt-3 md:mt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span>Session Attempts:</span>
                    <span id="attemptCount">0</span>
                </div>
                <div class="flex justify-between">
                    <span>Session Best:</span>
                    <span id="bestDistance">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Status:</span>
                    <span id="gameStatus">Ready</span>
                </div>
            </div>
            <button id="resetGame" class="w-full mt-3 md:mt-4 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-200 text-sm md:text-base">
                🔄 Reset Session
            </button>
        </div>

        <!-- Global Leaderboard -->
        <div class="bg-white rounded-lg shadow-lg p-4 md:p-6">
            <h3 class="text-lg md:text-xl font-bold mb-3 md:mb-4">🏆 Top Hunters</h3>
            <div class="space-y-2">
                <?php if (!empty($globalLeaderboard)): ?>
                    <?php foreach ($globalLeaderboard as $index => $player): ?>
                        <div class="flex justify-between items-center p-2 text-sm <?= $index === 0 ? 'bg-yellow-50 border border-yellow-200 rounded' : '' ?>">
                            <div class="flex items-center space-x-2">
                                <span class="font-medium"><?= $index + 1 ?>.</span>
                                <span class="truncate">
                                    <?= $player['username'] ? esc($player['username']) : esc($player['name']) ?>
                                    <?php if ($player['username']): ?>
                                        <span class="text-xs text-green-600">👤</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <span class="font-bold text-blue-600 flex-shrink-0"><?= number_format($player['total_score']) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500 text-sm">No players yet. Be the first!</p>
                <?php endif; ?>
            </div>
            <div class="mt-3 md:mt-4 flex flex-col sm:flex-row gap-2 text-xs md:text-sm">
                <a href="/game/leaderboard" class="text-center text-blue-600 hover:text-blue-800 font-medium">
                    Global →
                </a>
                <a href="/game/treasures" class="text-center text-green-600 hover:text-green-800 font-medium">
                    All Treasures →
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Game variables
let map;
let currentScore = 0;
let attemptCount = 0;
let bestDistance = null;
let treasureMarker = null;
let guessMarkers = [];
let gameEnded = false;
let currentTreasureName = '<?= $currentTreasure ? esc($currentTreasure['name']) : '' ?>';

// Initialize map
function initMap() {
    map = L.map('map').setView([-2.5489, 118.0149], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    <?php if ($currentTreasure): ?>
    map.on('click', onMapClick);
    <?php endif; ?>
    
    // Invalidate size after a short delay to handle responsive issues
    setTimeout(() => {
        map.invalidateSize();
    }, 100);
}

// Handle map clicks
function onMapClick(e) {
    if (gameEnded) {
        updateGameMessage('🏆 Current treasure completed! Moving to next treasure...', 'info');
        return;
    }

    const lat = e.latlng.lat;
    const lng = e.latlng.lng;
    
    if (!lat || !lng || isNaN(lat) || isNaN(lng)) {
        updateGameMessage('❌ Invalid coordinates. Please try clicking again.', 'error');
        return;
    }

    const markerColor = getMarkerColor(guessMarkers.length);
    const guessMarker = L.marker([lat, lng], {
        icon: L.icon({
            iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-${markerColor}.png`,
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(map);
    
    guessMarkers.push(guessMarker);

    let requestData = {
        latitude: lat,
        longitude: lng
    };

    fetch('/game/check', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        handleGuessResponse(data, guessMarker);
    })
    .catch(error => {
        console.error('Fetch error:', error);
        updateGameMessage('❌ Connection error: ' + error.message, 'error');
        
        if (guessMarker) {
            map.removeLayer(guessMarker);
            guessMarkers.pop();
        }
    });
}

function getMarkerColor(attemptNumber) {
    const colors = ['red', 'blue', 'green', 'purple', 'orange', 'yellow', 'violet', 'grey', 'black'];
    return colors[attemptNumber % colors.length];
}

// Handle guess response
function handleGuessResponse(data, guessMarker) {
    console.log('Response data:', data);
    
    if (data.error) {
        updateGameMessage('❌ Error: ' + data.error, 'error');
        return;
    }
    
    attemptCount++;
    document.getElementById('attemptCount').textContent = attemptCount;

    // Update session score
    if (data.points > 0) {
        currentScore += data.points;
        document.getElementById('currentScore').textContent = currentScore;
    }

    // Update best distance
    const distance = data.distance || 0;
    if (bestDistance === null || distance < bestDistance) {
        bestDistance = distance;
        document.getElementById('bestDistance').textContent = Math.round(bestDistance) + 'm';
    }

    // Update game status
    updateGameStatus(data.gameStatus);

    // Show hint if available
    if (data.hint) {
        showHint(data.hint);
    }

    // Add popup to marker
    guessMarker.bindPopup(`
        <div class="text-center">
            <strong>Attempt ${attemptCount}</strong><br>
            Distance: ${Math.round(distance)}m<br>
            ${data.points > 0 ? `Points: +${data.points}` : 'No points'}
        </div>
    `);

    if (data.success || data.treasureCompleted) {
        // Treasure completed!
        gameEnded = true;
        
        if (data.treasureLocation) {
            treasureMarker = L.marker([data.treasureLocation.lat, data.treasureLocation.lng], {
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-gold.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })
            }).addTo(map);
            
            treasureMarker.bindPopup(`🏆 ${data.treasureLocation.name} Found!`).openPopup();
        }
        
        updateGameMessage(data.message, 'success');
        
        // If there's a next treasure, show reload option
        if (data.nextTreasure) {
            setTimeout(() => {
                if (confirm(`🎯 ${data.nextTreasure.name} is now your next treasure! Would you like to reload the page to start hunting for it?`)) {
                    location.reload();
                }
            }, 3000);
        } else {
            setTimeout(() => {
                if (confirm(`🏆 Congratulations! You've completed all treasures! Would you like to view your progress?`)) {
                    window.location.href = '/game/my-progress';
                }
            }, 3000);
        }
    } else {
        // Continue playing
        updateGameMessage(data.message, getMessageType(data.gameStatus));
    }
}

function updateGameStatus(status) {
    const statusElement = document.getElementById('gameStatus');
    const statusMap = {
        'success': '🏆 Found!',
        'very_close': '🔥 Very Close',
        'close': '👍 Close',
        'continue': '🧭 Searching'
    };
    statusElement.textContent = statusMap[status] || 'Ready';
}

function showHint(hint) {
    const hintElement = document.getElementById('hintMessage');
    hintElement.textContent = hint;
    hintElement.classList.remove('hidden');
    
    setTimeout(() => {
        hintElement.classList.add('hidden');
    }, 10000);
}

function getMessageType(gameStatus) {
    switch (gameStatus) {
        case 'very_close': return 'warning';
        case 'close': return 'info';
        default: return 'error';
    }
}

// Update game message
function updateGameMessage(message, type) {
    const messageEl = document.getElementById('gameMessage');
    messageEl.textContent = message;
    
    messageEl.className = 'text-center p-3 md:p-4 rounded-lg text-sm md:text-base';
    
    if (type === 'success') {
        messageEl.className += ' bg-green-50 text-green-800 border border-green-200';
    } else if (type === 'warning') {
        messageEl.className += ' bg-orange-50 text-orange-800 border border-orange-200';
    } else if (type === 'info') {
        messageEl.className += ' bg-blue-50 text-blue-800 border border-blue-200';
    } else if (type === 'error') {
        messageEl.className += ' bg-red-50 text-red-800 border border-red-200';
    } else {
        messageEl.className += ' bg-blue-50 text-blue-800';
    }
}

// Reset game
function resetGame() {
    // Clear markers
    guessMarkers.forEach(marker => map.removeLayer(marker));
    guessMarkers = [];
    
    if (treasureMarker) {
        map.removeLayer(treasureMarker);
        treasureMarker = null;
    }
    
    // Reset stats
    attemptCount = 0;
    bestDistance = null;
    gameEnded = false;
    document.getElementById('attemptCount').textContent = '0';
    document.getElementById('bestDistance').textContent = '-';
    document.getElementById('gameStatus').textContent = 'Ready';
    document.getElementById('hintMessage').classList.add('hidden');
    
    updateGameMessage(`Click on the map to start hunting for ${currentTreasureName}!`, 'info');
}

// Event listeners
document.getElementById('resetGame').addEventListener('click', resetGame);

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', initMap);

// Handle window resize for map
window.addEventListener('resize', function() {
    if (map) {
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    }
});
</script>
<?= $this->endSection() ?>
