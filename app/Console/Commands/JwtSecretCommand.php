<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class JwtSecretCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jwt:secret';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Jwt secret for jwt token';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = base_path('.env');
        $var = "JWT_SECRET";
        $secret = Str::random(120);

        if (file_exists($path)) {
            if (strpos(file_get_contents($path), $var)) {
                echo "Secret is already generated \n";
                return false;
            } else {
                file_put_contents($path, "\n$var=$secret", FILE_APPEND);
                file_put_contents($path, "\nJWT_ALGO=HS256", FILE_APPEND);
                echo "Secret succesfully generated \n";
                return true;
            }
        }
        echo "Secret succesfully generated \n";
        return true;
    }
}
