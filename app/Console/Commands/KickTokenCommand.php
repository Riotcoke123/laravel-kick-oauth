<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\KickOAuthService;

class KickTokenCommand extends Command
{
    protected $signature = 'kick:token';
    protected $description = 'Get a Kick App Access Token (client credentials)';

    public function handle(KickOAuthService $kick)
    {
        $token = $kick->getAppAccessToken();
        $this->info('Access Token: ' . $token['access_token']);
        $this->info('Expires in: ' . $token['expires_in'] . 's');
        return 0;
    }
}
