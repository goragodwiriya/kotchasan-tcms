<?php
/**
 * @filesource index/controllers/main.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Main;

use Kotchasan\Http\Request;

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
     * โมดูลหลัก
     *
     * @param Request $request
     * @return Object
     */
    public static function init(Request $request)
    {
        // รับค่าจาก $_GET['module'] ถ้าไม่มีการส่งค่ามาจะคืนค่า pages โดยคืนค่าเป็น string ที่ตัวแปร module
        // method filter() กำหนดให้รับค่าเฉพาะตัวอักษรที่กำหนดเท่านั้น
        $module = $request->get('module', 'pages')->filter('a-z');
        // ตรวจสอบ Controller ที่ต้องการ
        $class = 'Index\\'.ucfirst($module).'\Controller';
        if (method_exists($class, 'init')) {
            // โหลด Controller ที่เรียก
            $controller = createClass($class)->init($request);
        } else {
            // ไม่พบ Controller เรียก Pagenotfound controller
            $controller = \Index\PageNotFound\Controller::render();
        }
        // คืนค่าข้อมูลโมดูล

        return (object) array(
            'module' => $controller->module,
            'title' => $controller->title,
            'detail' => \Index\Main\View::render($controller->detail),
        );
    }
}
