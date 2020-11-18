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
use Kotchasan\Template;

/**
 * Description
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        // รับค่าจาก $_GET['module'] ถ้าไม่มีการส่งค่ามาจะคืนค่า home โดยคืนค่าเป็น string ที่ตัวแปร module
        // method filter() กำหนดให้รับค่าเฉพาะตัวอักษรที่กำหนดเท่านั้น
        $module = $request->get('module', 'home')->filter('a-z');
        // กำหนดค่า template ที่ใช้งานอยู่
        Template::init('skin/'.self::$cfg->skin);
        // เรียกไปยัง Index\Main\Controller เพื่อโหลดหน้าเว็บ
        $index = createClass('Index\Main\Controller')->init($request, $module);
        // เริ่มต้นใช้งาน View
        $view = new \Kotchasan\View;
        // ใส่เนื้อหาลงใน View ตามที่กำหนดใน Template
        $view->setContents(array(
            // ข้อความจาก View แสดงบน title bar
            '/{TITLE}/' => $index->title,
            // เนื้อหาหน้า View ที่เรียกใช้งาน
            '/{CONTENT}/' => $index->detail,
            // แสดงเมนู
            '/{TOPMENU}/' => \Kotchasan\Menu::render(\Index\Menu\Model::get(), $index->module),
            // จำนวน Query
            '/{QURIES}/' => \Kotchasan\Database\Driver::queryCount(),
        ));
        // โหลด template หลัก (index.html)
        $template = Template::load('', '', 'index');
        // ส่งออก HTML
        echo $view->renderHTML($template);
    }
}
