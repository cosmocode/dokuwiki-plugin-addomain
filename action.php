<?php
/**
 * DokuWiki Plugin addomain (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andreas Gohr <gohr@cosmocode.de>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_LF')) define('DOKU_LF', "\n");
if(!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC.'lib/plugins/');

require_once DOKU_PLUGIN.'action.php';

class action_plugin_addomain extends DokuWiki_Action_Plugin {

    public function register(Doku_Event_Handler &$controller) {

        $controller->register_hook('AUTH_LOGIN_CHECK', 'BEFORE', $this, 'handle_auth_login_check');
        $controller->register_hook('HTML_LOGINFORM_OUTPUT', 'BEFORE', $this, 'handle_html_loginform_output');

    }

    public function handle_auth_login_check(Doku_Event &$event, $param) {
        /** @var auth_ad */
        global $auth;
        if(!is_a($auth, 'auth_ad')) return; // AD not even used

        if(!empty($_REQUEST['dom'])){
            $usr = $auth->cleanUser($event->data['user']);
            $dom = $auth->_userDomain($usr);
            if(!$dom){
                $usr = "$usr@".$_REQUEST['dom'];
            }
            $_REQUEST['u'] = $usr;
            $event->data['user'] = $usr;
        }
    }

    public function handle_html_loginform_output(Doku_Event &$event, $param) {
        global $conf;
        /** @var auth_ad */
        global $auth;
        if(!is_array($conf['auth']['ad'])) return; // no AD config
        if(!is_a($auth, 'auth_ad')) return; // AD not even used

        // find configured domains
        $domains = array();
        foreach($conf['auth']['ad'] as $key => $val) {
            if(is_array($val)) {
                $domains[$key] = $key;
            }
        }
        if(!$domains) return; // nothing to do

        // add default domain, using the name from account suffix
        $domains[''] = ltrim($conf['auth']['ad']['account_suffix'],'@');

        ksort($domains);

        /** @var $form Doku_Form */
        $form    =& $event->data;

        // any default?
        $dom = '';
        if(isset($_REQUEST['u'])) {
            $usr = $auth->cleanUser($_REQUEST['u']);
            $dom = $auth->_userDomain($usr);

            // update user field value
            if($dom){
                $usr = $auth->_userName($usr);
                $pos = $form->findElementByAttribute('name', 'u');
                $ele =& $form->getElementAt($pos);
                $ele['value'] = $usr;
            }
        }

        // add select box
        $element = form_makeListboxField('dom', $domains, $dom, $this->getLang('domain'), '', 'block');
        $pos     = $form->findElementByAttribute('name', 'p');
        $form->insertElement($pos + 1, $element);
    }

}

// vim:ts=4:sw=4:et:
