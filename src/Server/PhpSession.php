<?php declare(strict_types=1);

namespace AlanVdb\Server;

use RuntimeException;

class PhpSession
{
    /**
     * Démarrer la session si elle n'est pas déjà active
     * 
     * @throws RuntimeException Si la session ne peut pas être démarrée
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            if (!session_start()) {
                throw new RuntimeException('Impossible de démarrer la session PHP');
            }
        }
    }

    /**
     * Définir une variable de session
     * 
     * @param string $key
     * @param mixed $value
     */
    public static function set(string $key, $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Récupérer une variable de session
     * 
     * @param string $key
     * @param mixed $default Valeur par défaut si la clé n'existe pas
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Vérifier si une clé de session existe
     * 
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        self::start();
        return array_key_exists($key, $_SESSION);
    }

    /**
     * Supprimer une variable de session
     * 
     * @param string $key
     */
    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Détruire complètement la session
     */
    public static function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
            session_write_close();
        }
    }

    /**
     * Régénérer l'ID de session (protection contre le fixation)
     * 
     * @param bool $deleteOldSession Supprimer l'ancienne session
     */
    public static function regenerate(bool $deleteOldSession = true): void
    {
        self::start();
        session_regenerate_id($deleteOldSession);
    }

    /**
     * Récupérer l'ID de session courant
     * 
     * @return string
     */
    public static function getId(): string
    {
        self::start();
        return session_id();
    }
}
