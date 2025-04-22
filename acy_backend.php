<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Log\Log;

class plgSystemCustomCode extends CMSPlugin
{
    private function logMessage($message)
    {
        Log::add($message, Log::INFO, 'custom_plugin');
    }

    public function onAfterInitialise()
    {
        $app = Factory::getApplication();

        // Ensure this runs only in the administrator panel
        if (!$app->isClient('administrator')) {
            $this->logMessage("Plugin aborted: Not in admin panel");
            return;
        }

        // Log execution
        //$this->logMessage("Custom Joomla Plugin is executing");
        //$app->enqueueMessage('Custom Joomla Plugin is executing', 'notice');

        // Get the selected user from plugin parameters
        $selectedUserId = (int) $this->params->get('selected_user', 0);
        $selectedUser = Factory::getUser($selectedUserId)->username;
        $this->logMessage("Selected user from settings: $selectedUser (ID: $selectedUserId)");

        // Get the selected class from plugin parameters
        $selectedClass = trim($this->params->get('selected_class', '.acym__header__notification'));
        if (empty($selectedClass)) {
            $selectedClass = '.acym__header__notification'; // Default fallback
        }
        $this->logMessage("Selected class from settings: $selectedClass");

        // Get the current logged-in user
        $user = Factory::getUser();
        $username = $user->username;
        $this->logMessage("Logged in as: $username");

        // Check if the logged-in user matches the selected user
        if ($selectedUserId > 0 && $username === $selectedUser) {
            $this->logMessage("User match: $selectedUser");
            $doc = Factory::getDocument();
            $doc->addScriptDeclaration("document.addEventListener('DOMContentLoaded', function() {
                console.log('Custom Joomla Plugin Loaded');

                function hideElements() {
                    var cells = document.querySelectorAll('$selectedClass');
                    console.log('Found ' + cells.length + ' elements with class $selectedClass');

                    if (cells.length > 0) {
                        cells.forEach(function(cell) {
                            cell.style.display = 'none';
                            console.log('Hiding element: ', cell);
                        });

                        var observer = new MutationObserver(hideElements);
                        observer.observe(document.body, { childList: true, subtree: true });
                    } else {
                        console.log('No elements found with class $selectedClass');
                    }
                }

                // Initial execution with delay
                setTimeout(hideElements, 1000);
            });");
        } else {
            $this->logMessage("User does not match, skipping script");
        }
    }
}
