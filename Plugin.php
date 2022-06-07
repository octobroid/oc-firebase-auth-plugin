<?php namespace Octobro\FirebaseAuth;

use App;
use System\Classes\PluginBase;
use Octobro\FirebaseAuth\Classes\FirebaseGrant;
use League\OAuth2\Server\AuthorizationServer;
use Laravel\Passport\Passport;
use Laravel\Passport\Bridge\RefreshTokenRepository;

/**
 * Auth Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Firebase Auth',
            'description' => 'Firebase grant type for OAuth2 plugin.',
            'author'      => 'Octobro',
            'icon'        => 'icon-key'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        App::register(\Kreait\Laravel\Firebase\ServiceProvider::class);
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        app(AuthorizationServer::class)->enableGrantType(
            new FirebaseGrant(
                $this->app->make(RefreshTokenRepository::class)
            ), Passport::tokensExpireIn()
        );
    }

}
