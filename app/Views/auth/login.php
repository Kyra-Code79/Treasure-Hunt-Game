<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6 md:p-8">
    <h2 class="text-xl md:text-2xl font-bold text-center mb-4 md:mb-6">🏴‍☠️ Welcome Back!</h2>
    <p class="text-sm md:text-base text-gray-600 text-center mb-4 md:mb-6">Login to continue your treasure hunting adventure</p>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/auth/login">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                Username or Email
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 md:py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm md:text-base" 
                   id="username" name="username" type="text" placeholder="Username or email" 
                   value="<?= old('username') ?>" required>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                Password
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 md:py-3 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline text-sm md:text-base" 
                   id="password" name="password" type="password" placeholder="Your password" required>
        </div>
        
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 md:py-3 px-4 rounded focus:outline-none focus:shadow-outline w-full text-sm md:text-base" 
                    type="submit">
                🎮 Login & Hunt Treasures
            </button>
        </div>
    </form>
    
    <div class="text-center mt-4 md:mt-6">
        <p class="text-gray-600 text-sm md:text-base">Don't have an account?</p>
        <a href="/auth/register" class="text-blue-500 hover:text-blue-700 font-bold text-sm md:text-base">Register here</a>
    </div>
</div>
<?= $this->endSection() ?>
