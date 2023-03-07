<?php if (session()->hasFlash('success')): ?>
     <div class="mt-5 max-w-7xl mx-auto bg-green-100 border border-green-400 text-green-700 px-4 py-3">
          <span class="block sm:inline text-xs"><?= session()->getFlash('success'); ?></span>
     </div>
<?php endif; ?>
