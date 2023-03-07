<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <a href="<?= main_url() ?>" class="flex ml-2 md:mr-24 text-gray-700 font-bold text-xl"><?= env('APP_NAME') ?></a>
            </div>
            <div class="flex items-center">
                <div class="flex items-center ml-3">
                    <a href="<?= main_url() ?>/logout" class="tracking-wider text-sm text-gray-500">Logout</a>
                </div>
            </div>
        </div>
    </div>    
</nav>