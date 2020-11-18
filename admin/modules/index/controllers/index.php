<?php
/**
 * @filesource index/controllers/index.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Index;

use Kotchasan\Http\Request;
use Kotchasan\Login;
use Kotchasan\Template;

/**
 * Controller หลัก สำหรับแสดง backend
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{

    /**
     * แสดงผลหน้าหลักเว็บไซต์
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        // เริ่มต้นใช้งาน session
        $request->initSession();
        // ตรวจสอบการ login
        Login::create();
        // กำหนด skin ให้กับ template
        Template::init('skin/admin');
        // backend
        $view = new \Kotchasan\View;
        if ($login = Login::isMember()) {
            // Controller หลัก
            $main = \Index\Main\Controller::init($request);
            // class สำหรับ body (index.html)
            $bodyclass = 'mainpage';
        } else {
            // Controller หน้า login
            $main = \Index\Login\Controller::init($request);
            // class สำหรับ body (index.html)
            $bodyclass = 'loginpage';
        }
        // เนื้อหา
        $view->setContents(array(
            // main template
            '/{MAIN}/' => $main->detail,
            // title
            '/{TITLE}/' => $main->title,
            // class สำหรับ body (index.html)
            '/{BODYCLASS}/' => $bodyclass,
        ));
        if ($login) {
            $view->setContents(array(
                // แสดงชื่อคน Login
                '/{LOGINNAME}/' => $login['username'],
                // แสดงเมนู
                '/{TOPMENU}/' => \Kotchasan\Menu::render(\Index\Menu\Model::get(), $main->module),
            ));
        }
        // ส่งออก เป็น HTML
        echo $view->renderHTML();
    }
}
