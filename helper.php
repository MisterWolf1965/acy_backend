<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class plgSystemCustomCodeHelper
{
    /**
     * Get the username of the logged-in user
     *
     * @return string The username of the logged-in user or an empty string if no user is logged in
     */
    public static function getLoggedInUser()
    {
        $user = Factory::getUser();
        return $user->guest ? '' : $user->username; // Return username or empty if guest
    }
}
