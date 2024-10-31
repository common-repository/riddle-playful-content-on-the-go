<?php

namespace src\Api;

use Exception;
use Riddle\Api\Client;
use src\classes\UserSettings;

/**
 * Class for connections with the Riddle 2.0 API.
 */
class RiddleLoaderV2 implements RiddleLoaderInterface
{
    // == STATIC FUNCTIONS

    private static $riddleLoader; // in memory

    public static function getLoader(): ?RiddleLoaderV2
    {
        if (static::isAuthorized()) {
            return static::$riddleLoader = static::$riddleLoader ?? new RiddleLoaderV2(); // keep loader in memory if built once
        }

        return null;
    }

    public static function isAuthorized(): bool
    {
        return null !== static::getAccessToken();
    }

    public static function getAccessToken(): ?string
    {
        $accessToken = \get_option(UserSettings::ACCESSTOKEN_OPTION, null);

        if (null === $accessToken || '' === \trim($accessToken)) {
            return null;
        }

        return $accessToken;
    }

    // == CLASS FUNCTIONS

    public function getRiddle($riddleId): ?array
    {
        return $this->getAPIClient()->riddle()->getRiddle($riddleId);
    }

    public function getEmbedCode($riddleId, array $params): ?string
    {
        return $this->getAPIClient()->riddle()->getEmbedCode($riddleId, $params);
    }

    public function disconnect(): void
    {
        try {
            $this->getAPIClient()->accessToken()->revoke();
        } catch (Exception $ex) {
            // silence this exception - disconnecting should always work.
            // if we do not do this here the user could get stuck in an infinite disconnect loop.
        }

        \delete_option(UserSettings::ACCESSTOKEN_OPTION);
        \delete_option(UserSettings::SELECTED_TEAM_OPTION); // maybe the user does not have access to the current team id - better delete it
    }

    public function showV2Riddles(bool $showV2Riddles): void
    {
        \update_option(UserSettings::SHOW_V2_RIDDLES, $showV2Riddles);
    }

    public static function shouldShowV2Riddles(): bool
    {
        return \get_option(UserSettings::SHOW_V2_RIDDLES, false);
    }

    public function getAPIClient(): Client
    {
        return new Client($this->getAccessToken());
    }
}