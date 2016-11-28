<?php
namespace WeatherBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use WeatherBundle\HTTPClients\WundergroundHttpClient;
use WeatherBundle\HTTPClients\YahooHttpClient;
use WeatherBundle\Parsers\WundergroundDataParser;
use WeatherBundle\Parsers\YahooDataParser;
use WeatherBundle\Providers\CachedWeatherProvider;
use WeatherBundle\Providers\DelegateWeatherProvider;
use WeatherBundle\Providers\WundergroundWeatherProvider;
use WeatherBundle\Providers\YahooWeatherProvider;

class WeatherExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // @link: http://symfony.com/doc/current/service_container/definitions.html
        // Register parsers
        $container->register('weather.yahoo_weather_parser', YahooDataParser::class);
        $container->setAlias('weather_parser_yahoo', 'weather.yahoo_weather_parser');

        $container->register('weather.wunderground_weather_parser', WundergroundDataParser::class);
        $container->setAlias('weather_parser_wunderground', 'weather.wunderground_weather_parser');

        // Register Http clients

        $container->register('weather.yahoo_HTTPClient', YahooHttpClient::class);
        $container->setAlias('weather_HTTPClient_yahoo', 'weather.yahoo_HTTPClient');

        $container->register('weather.wunderground_HTTPClient', WundergroundHttpClient::class);
        $container->setAlias('weather_HTTPClient_wunderground', 'weather.wunderground_HTTPClient');


        // Register all the providers
        // Yahoo


        $yahooDefinition = new Definition(YahooWeatherProvider::Class, [
            new Reference('weather_HTTPClient_yahoo'),
            new Reference('weather_parser_yahoo'),
            $config['providers']['yahoo']['base_url']
        ]);
        $container->setDefinition('weather.yahoo_weather_provider', $yahooDefinition);
        $container->setAlias('weather_yahoo', 'weather.yahoo_weather_provider');

        // Wunderground

        if (isset($config['providers']['wunderground']) || $config['provider'] == 'wunderground') {
            $wundergroundDefinition = new Definition(WundergroundWeatherProvider::Class, [
                new Reference('weather_HTTPClient_wunderground'),
                new Reference('weather_parser_wunderground'),
                $config['providers']['wunderground']['base_url'],
                $config['providers']['wunderground']['api_key']
            ]);
            $container->setDefinition('weather.wunderground_weather_provider', $wundergroundDefinition);
            $container->setAlias('weather_wunderground', 'weather.wunderground_weather_provider');
        }

        // Delegating
        if (isset($config['providers']['delegating']) || $config['provider'] == 'delegating') {
            $delegatingDefinition = new Definition(DelegateWeatherProvider::Class, [
                $this->get_providers_definitions($config['providers']['delegating']['providers'], $container)
            ]);
            $container->setDefinition('weather.delegating_weather_provider', $delegatingDefinition);
            $container->setAlias('weather_delegating', 'weather.delegating_weather_provider');
        }

        // Cached
        if (isset($config['providers']['cached']) || $config['provider'] == 'cached') {
            $cachedDefinition = new Definition(CachedWeatherProvider::Class, [
                $this->get_providers_definition($config['providers']['cached']['provider'], $container),
                $config['providers']['cached']['ttl']
            ]);
            $container->setDefinition('weather.cached_weather_provider', $cachedDefinition);
            $container->setAlias('weather_cached', 'weather.cached_weather_provider');
        }


        $container->setAlias('weather', 'weather_' . $config['provider']);
    }

    /**
     * @param array $providers_titles
     * @param ContainerBuilder $container
     * @return array
     */
    private function get_providers_definitions(array $providers_titles, ContainerBuilder $container): array
    {
        $providers = [];

        foreach ($providers_titles as $provider_title) {
            if ($container->hasDefinition('weather_' . $provider_title))
                $providers[] = new Reference('weather_' . $provider_title);
        }

        if (count($providers) == 0)
            $providers[] = new Reference('weather_yahoo');

        return $providers;
    }


    /**
     * @param String $provider_title
     * @param ContainerBuilder $container
     * @return Reference
     */
    private function get_providers_definition(String $provider_title, ContainerBuilder $container): Reference
    {

        if ($container->hasDefinition('weather_' . $provider_title)) {
            return new Reference('weather_' . $provider_title);
        }

        return new Reference('weather_yahoo');

    }
}