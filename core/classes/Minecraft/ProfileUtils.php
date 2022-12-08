<?php
/**
 * Provides methods to generate a MinecraftProfile from a username or UUID.
 *
 * @package RadomeWEB\Minecraft
 * @see MinecraftProfile
 * @author Daniel Fanara
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */
class ProfileUtils {

    /**
     * Get a MinecraftProfile from a username or UUID.
     *
     * @param string $identifier Either the player's Username or UUID.
     * @return MinecraftProfile|null Returns null if fetching of profile failed. Else returns completed user profile.
     */
    public static function getProfile(string $identifier): ?MinecraftProfile {
        if (strlen($identifier) <= 16) {
            $result = self::getUUIDFromUsername($identifier);
            if ($result === null) {
                return null;
            }
            $uuid = $result['uuid'];
        } else {
            $uuid = $identifier;
        }

        $client = $uuid;

        if (!$client->hasError()) {
            $data = $client->json(true);
            return new MinecraftProfile($data['name'], $data['id'], $data['properties']);
        }

        return null;
    }

    /**
     * Get a Minecraft UUID from a Minecraft username.
     *
     * @param string $username Minecraft username.
     * @return array (Key => Value) "username" => Minecraft username (properly capitalized) "uuid" => Minecraft UUID or null
     */
    private static function getUUIDFromUsername(string $username): ?array {
        if (strlen($username) > 16) {
            return ['username' => '', 'uuid' => ''];
        }

        $result = $username;

        return null;
    }

    /**
     * Generate an offline minecraft UUID v3 based on the case sensitive player name.
     *
     * @param string $username
     * @return array
     */
    public static function getOfflineModeUuid(string $username): array {
        return [
            'uuid' => $username,
            'username' => $username
        ];
    }

}
