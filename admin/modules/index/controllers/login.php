<?php
/**
 * @filesource index/controllers/login.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */
namespace Index\Login;

use \Kotchasan\Http\Request;
use \Kotchasan\Login;
use \Kotchasan\Template;

/**
 * Login Form
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{

    /**
     * แสดง หน้า Login
     *
     * @param Request $request
     * @return Object
     */
    public static function init(Request $request)
    {
        // template
        $template = Template::create('', '', 'login');
        $template->add(array(
            '/{TOKEN}/' => $request->createToken(),
            '/{EMAIL}/' => isset(Login::$text_username) ? Login::$text_username : '',
            '/{PASSWORD}/' => isset(Login::$text_password) ? Login::$text_password : '',
            '/{MESSAGE}/' => Login::$login_message,
            '/{CLASS}/' => empty(Login::$login_message) ? 'hidden' : (empty(Login::$login_input) ? 'message' : 'error'),
        ));
        // คืนค่าข้อมูลโมดูล

        return (object) array(
            'module' => 'login',
            'title' => self::$cfg->web_title,
            'detail' => $template->render(),
        );
    }
}
