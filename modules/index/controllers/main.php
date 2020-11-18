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

use \Kotchasan\Http\Request;
use \Kotchasan\Template;

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
     * เริ่มต้นใช้งานโมดูล
     * อ่านข้อมูลโมดูลออกมา แล้วส่งให้ View
     * ข้อมูลจาก View ส่งกลับไปให้ Controller หลัก
     *
     * @param Request $request
     * @param string $module
     * @return Object
     */
    public function init(Request $request, $module)
    {
        // ตรวจสอบว่ามีไฟล์โมดูลที่ต้องการหรือไม่
        $index = \Index\Module\Model::get($module);
        if (!$index) {
            $index = createClass('Index\Pagenotfound\Controller')->init();
        }
        // เริ่มต้นใช้งาน View ของโมดูล Main
        $view = new \Kotchasan\View;
        // ใส่เนื้อหาลงใน View ตามที่กำหนดใน Template
        $view->setContents(array(
            // หัวข้อ
            '/{TOPIC}/' => $index['topic'],
            // เนื้อหา
            '/{DETAIL}/' => $index['detail'],
        ));
        // โหลด template หน้า main (main.html)
        $template = Template::load('', '', 'main');
        // คืนค่าข้อมูลโมดูล

        return (object) array(
            'module' => $index['module'],
            'title' => $index['topic'],
            'detail' => $view->renderHTML($template),
        );
    }
}
