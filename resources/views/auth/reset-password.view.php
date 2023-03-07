<?php 
use App\Controllers\Auth\ResetPasswordController;
    global $pageTitle;
    $pageTitle = 'Reset Password'; 
    include view_path() . 'layouts/header.view.php'; 
    ResetPasswordController::getURLValidation();
?>   

    <div class="flex flex-col justify-between mt-32">          
        <h2 class="my-6 text-center text-3xl font-extrabold text-gray-700">Reset Your Password</h2>
        <form action="<?= main_url() ?>/reset/password" method="POST" class="w-80 mx-auto flex flex-col justify-between gap-3">
        <input type="hidden" name="email" value="<?= $_GET['email'] ?? false ?>">
        <input type="hidden" name="token" value="<?= $_GET['token'] ?? false ?>">
            <div>
                <input value="<?= $_GET['email'] ?? 'ERROR' ?>" disabled readonly  type="email" class="appearance-none relative block w-full px-3 py-2 border bg-gray-300 border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email">
            </div>

            <div>
                <input id="password" name="password" type="password" autocomplete="password" class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                <?php if (session()->hasFlash('password_errors')): ?>
                        <p class="text-xs text-red-500">
                            <?= session()->getFlash('password_errors')[0]; ?>
                        </p>
                <?php endif; ?>
            </div>

            <div>
                <label for="password_confirmation" class="sr-only">Password Confirmation</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="password_confirmation" class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password Confirmation">
            </div>

            <div>
                <button type="submit" class="uppercase tracking-widest group w-full py-2 px-4 border border-transparent text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Reset Password
                </button>
            </div>


            <?php include view_path() . 'layouts/success-message.view.php'; ?>   
            <?php include view_path() . 'layouts/fail-message.view.php'; ?>   

        </form>
    </div>

<?php include view_path() . 'layouts/footer.view.php'; ?>