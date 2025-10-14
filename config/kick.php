<?php

return [
    'client_id'     => env('KICK_CLIENT_ID'),
    'client_secret' => env('KICK_CLIENT_SECRET'),
    'redirect_uri'  => env('KICK_REDIRECT_URI'),

    'authorize_url' => env('KICK_AUTHORIZE_URL', 'https://id.kick.com/oauth/authorize'),
    'token_url'     => env('KICK_TOKEN_URL', 'https://id.kick.com/oauth/token'),
    'revoke_url'    => env('KICK_REVOKE_URL', 'https://id.kick.com/oauth/revoke'),

    'scopes'        => ['user:read', 'chat:read'], // Adjust scopes as needed
];
