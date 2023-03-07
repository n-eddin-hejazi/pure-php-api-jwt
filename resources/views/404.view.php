<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php 
        global $pageTitle;
        $pageTitle = 'Not Found 404';  
    ?>
    <title><?= getTitle() ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

  
    <div class="flex items-center justify-center h-screen bg-[#f3f3f3]">
        <div class="max-w-lg mx-auto">
            <div class="bg-white shadow-md px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <h1 class="text-3xl font-bold text-gray-800">Oops!</h1>
                    <p class="text-gray-700">We couldn't find the page you were looking for.</p>
                </div>
       
                <div class="flex justify-center mt-8">
                    <a href="<?= main_url() ?>" class="uppercase tracking-widest group w-full py-2 px-4 border border-transparent text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Go back to homepage</a>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
