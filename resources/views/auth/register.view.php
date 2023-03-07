<?php 
use App\Controllers\Auth\RegisterController;
use App\Core\Support\Session;
global $pageTitle;
$pageTitle = 'Register'; 
include view_path() . 'layouts/header.view.php'; 
?>   


     <!-- title of page -->
     <div class="flex flex-col justify-between mt-32">
          
          <h2 class="my-6 text-center text-3xl font-extrabold text-gray-700">Register for new Account</h2>
          <form action="<?= main_url() ?>/register" method="POST" class="w-80 mx-auto flex flex-col justify-between gap-3">
               
               <div>
                    <label for="name" class="sr-only">Name</label>
                    <input id="name" name="name" type="text" autocomplete="name" value="<?= old('name') ?>" class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Name">
                    <?php if (session()->hasFlash('name_errors')): ?>
                         <p class="text-xs text-red-500">
                              <?= session()->getFlash('name_errors')[0]; ?>
                         </p>
                    <?php endif; ?>
               </div>

               <div>
                    <label for="email" class="sr-only">Email</label>
                    <input id="email" name="email" type="text" autocomplete="email" value="<?= old('email') ?>" class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email">
                    <?php if (session()->hasFlash('email_errors')): ?>
                         <p class="text-xs text-red-500">
                              <?= session()->getFlash('email_errors')[0]; ?>
                         </p>
                    <?php endif; ?>
               </div>

               <div>
                    <label for="password" class="sr-only">Password</label>
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
                         Register
                    </button>
               </div>


               <?php include view_path() . 'layouts/success-message.view.php'; ?>   
               <?php include view_path() . 'layouts/fail-message.view.php'; ?>   

          </form>
     </div>


<?php include view_path() . 'layouts/footer.view.php'; ?>