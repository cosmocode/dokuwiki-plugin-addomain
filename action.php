<?php
/**
 * DokuWiki Plugin addomain (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andreas Gohr <gohr@cosmocode.de>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_LF')) define('DOKU_LF', "\n");
if (!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once DOKU_PLUGIN.'action.php';

class action_plugin_addomain extends DokuWiki_Action_Plugin {

    public function register(Doku_Event_Handler &$controller) {

       $controller->register_hook('AUTH_LOGIN_CHECK', 'FIXME', $this, 'handle_auth_login_check');
       $controller->register_hook('HTML_LOGINFORM_OUTPUT', 'FIXME', $this, 'handle_html_loginform_output');
   
    }

    public function handle_auth_login_check(Doku_Event &$event, $param) {
    }

    public function handle_html_loginform_output(Doku_Event &$event, $param) {
    }

}

// vim:ts=4:sw=4:et:
