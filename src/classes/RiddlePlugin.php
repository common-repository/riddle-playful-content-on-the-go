<?php

namespace src\classes;

use src\Api\RiddleLoaderV2;
use src\Api\ShortcodeFilter;
use src\Api\V1\RiddleLoader;
use src\classes\UserSettings;
use src\classes\Landingpage\RiddleLandingpageLoader;

class RiddlePlugin
{
    private $cdnBasePath = 'https://cdn.riddle.com/website/wp-plugin/';

    /**
     * Starts the plugin and executes all necessary actions/filters/...
     */
    public function run() 
    {
        \add_action('admin_menu', 'loadRiddleMenu'); // add a admin sidebar menu

        if ((RiddleLoader::isAuthorized() || RiddleLoaderV2::isAuthorized()) && !$this->isOnRiddleAdminPage()) {
            (new ShortcodeFilter())->init();
            $this->addAction('enqueue_block_editor_assets', [$this, 'addRiddleBlock']);
            RiddleLandingpageLoader::addShortcodeFilter();
        }

        if ($this->isOnRiddleAdminPage()) { // these scripts are only needed on riddle admin pages
            $this->addAction('init', 'src\classes\RiddlePlugin::initSession');
            $this->addAction('wp_loaded', 'src\controller\AdminController::processConnectOauthCallbackCode');
            $this->addAction('wp_loaded', 'src\controller\AdminController::processUserValues');

            if (RiddleLoaderV2::isAuthorized()) { // different actions are needed for the different versions
                $this->addAction('wp_loaded', 'src\controller\AdminController::processDisconnect');
            } else {
                $this->addAction('wp_loaded', 'src\controller\RiddleAdminController::riddleProcessDisconnect');
            }

            // V1 stuff
            $this->addAction('wp_loaded', 'src\controller\RiddleCRPController::riddleHandleLandingpageOperation');
            $this->addAction('wp_loaded', 'src\controller\RiddleCRPController::riddleHandleLandingpageDeletion');
            $this->addAction('wp_loaded', 'src\controller\RiddleLeaderboardLeadController::handleLeaderboardLeadsDownload');

            $this->addAction('admin_enqueue_scripts', array($this, 'loadAssets')); // CSS / JS files
        }
    }

    /**
     * Adds an action to wordpress
     */
    public function addAction($action, $callable) 
    {
        \add_action($action, $callable);
    }

    /**
     * Adds a stylesheet to wordpress
     * @param $stylesheet (string) stylesheet name WITHOUT file extension
     */
    public static function addStylesheet($stylesheet, bool $local = true) 
    {
        $sheetPath = $local ? RIDDLE_URL_PATH . '/public/css/' . $stylesheet . '.css' : $stylesheet;
        \wp_enqueue_style( RIDDLE_PLUGIN_NAME . '_' . $stylesheet, $sheetPath, [], RIDDLE_PLUGIN_VERSION );
    }

    public static function addScript( $script, $enqueue = true, $dependencies = [], bool $local = true)
    {
        $scriptPath = $local ? RIDDLE_URL_PATH . '/public/js/' . $script . '.js' : $script;
        $scriptName = RIDDLE_PLUGIN_NAME . '_' . $script;

        if($enqueue) {
            \wp_enqueue_script($scriptName, $scriptPath, $dependencies, RIDDLE_PLUGIN_VERSION);
        } else {
            \wp_register_script($scriptName, $scriptPath, $dependencies, RIDDLE_PLUGIN_VERSION);
        }

        return $scriptName;
    }

    /**
     * Adds the Riddle Gutenberg Block to Wordpress.
     */
    public function addRiddleBlock()
    {
        $script = $this->addScript('https://cdn.riddle.com/website/wp-plugin/js/riddle-gutenberg-block-v5.1.0.js', false, ['wp-blocks', 'wp-element', 'wp-editor'], false);
        \register_block_type('riddle-plugin/riddle-gutenberg-block', ['editor_script' => $script]);
    }

    /**
     * Loading all the scripts the plugin needs.
     */
    public function addScripts()
    {
        \wp_enqueue_media(); // Wordpress media picker
        self::addScript($this->cdnBasePath . 'js/bootstrap.js', true, [], false);
        self::addScript($this->cdnBasePath . 'js/plugin.js', true, [], false);
    }

    /**
     * NEW in 4.1.2: our plugin no longer uses its own version of jquery; using Wordpress' jquery
     */
    public function addStylesheets()
    {
        \wp_enqueue_style('jquery'); // use WP jquery
        self::addStylesheet($this->cdnBasePath . 'css/bootstrap.css', false);
        self::addStylesheet($this->cdnBasePath . 'css/plugin_v2.css', false);
    }

    /**
     * Init the session if it is not available already
     * The plugin uses the session to verify that the user has access to the leaderboard preview and no external user can access any of the previews
     */
    public static function initSession()
    {
        if (\session_status() !== PHP_SESSION_ACTIVE) {
            \session_start();
        }
    }

    /**
     * This function loads all the scripts & stylesheets the plugin needs.
     * notice that these assets get only loaded if the user is on a riddle admin page.
     */
    public function loadAssets()
    {
        $this->addScripts();
        $this->addStylesheets();
    }

    private function isOnRiddleAdminPage($pagePrefix = 'riddle'): bool
    {
        $pageName = $_GET['page'] ?? false;

        if (!$pageName) {
            return false;
        }

        return \substr($pageName, 0, \strlen($pagePrefix)) === $pagePrefix;
    }

}