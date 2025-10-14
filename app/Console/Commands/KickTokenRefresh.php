<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KickToken;
use App\Services\KickOAuthService;

class KickTokenRefresh extends Command
{
    protected $signature = 'kick:refresh';
    protected $description = 'Refresh all expired Kick OAuth tokens';

    public function handle(KickOAuthService $kick)
    {
        $expired = KickToken::where('expires_at', '<', now())->get();

        if ($expired->isEmpty()) {
            $this->info('No expired tokens found.');
            return 0;
        }

        foreach ($expired as $token) {
            $kick->refreshToken($token);
            $this->info("Refreshed token for user #{$token->user_id}");
        }

        return 0;
    }
}
