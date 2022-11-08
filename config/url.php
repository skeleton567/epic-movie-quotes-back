<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Both frontend and backend urls
    |--------------------------------------------------------------------------
    */

   'frontend' => env(
       'BASE_URL',
       'http://localhost:5173'
   ),
   'backend' => env(
       'VITE_API_BASE_URL',
       'http://localhost:8000/api/'
   )

];
