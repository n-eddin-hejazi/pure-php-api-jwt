  <?php if (session()->hasFlash('fail')): ?>
     <div class="mt-5 w-80 mx-auto bg-red-100 border border-red-400 text-red-700 px-4 py-3">
          <span class="block sm:inline text-xs"><?= session()->getFlash('fail'); ?></span>
     </div>
<?php endif; ?>