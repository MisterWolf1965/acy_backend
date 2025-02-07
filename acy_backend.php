<?php
defined('_JEXEC') or die;

class plgSystemCustomCode extends JPlugin
{
    public function onAfterDispatch()
    {
        // Ensure we are in the Joomla administrator area
        if ($this->isAdmin()) {
            // Add a script to log that the plugin was triggered
            JFactory::getDocument()->addScriptDeclaration('console.log("Custom Code Plugin Triggered");');

            // Get the current user
            $user = JFactory::getUser();

            // Log the current user's name to verify it's working
            JFactory::getDocument()->addScriptDeclaration('
                var currentUser = "' . $user->username . '";
                console.log("Current user: " + currentUser);
                
                // Check if the logged-in user is "upabsch"
                if (currentUser === "upabsch") {
                    document.addEventListener("DOMContentLoaded", function() {
                        console.log("DOM fully loaded");

                        var cells = document.querySelectorAll(".acym__header__notification");
                        console.log("Found " + cells.length + " .acym__header__notification elements");

                        if (cells.length > 0) {
                            cells.forEach(function(cell) {
                                cell.style.display = "none";
                                console.log("Hiding element: ", cell);
                            });
                        } else {
                            console.log("No elements with the class .acym__header__notification found.");
                        }
                    });
                } else {
                    console.log("User is not upabsch, no changes made.");
                }
            ');
        }
    }

    /**
     * Check if we are in the Joomla admin panel
     *
     * @return bool True if in admin panel, false otherwise
     */
    private function isAdmin()
    {
        return JFactory::getApplication()->isAdmin();
    }
}
?>
