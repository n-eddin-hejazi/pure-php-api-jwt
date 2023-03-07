<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title><?= getTitle() ?></title>
     <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
     <?php include view_path() . 'admin/layouts/navbar.view.php'; ?>   
     <?php include view_path() . 'admin/layouts/sidebar.view.php'; ?>
     <div class="p-4 sm:ml-64 ">
          <div class="p-4  border border-1 border-gray-200 dark:border-gray-700 mt-14">
          <?php include view_path() . 'admin/layouts/success-message.view.php'; ?>   
          <?php include view_path() . 'admin/layouts/fail-message.view.php'; ?>   

