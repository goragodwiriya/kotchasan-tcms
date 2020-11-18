<?php
/**
 * @filesource index/controllers/menus.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */
namespace Index\Menus;

use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Login;

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
    public function init(Request $request)
    {
        // แอดมิน
        if (Login::isMember()) {
            // แสดงผล
            $section = Html::create('section');
            // breadcrumbs
            $breadcrumbs = $section->add('div', array(
                'class' => 'breadcrumbs',
            ));
            $ul = $breadcrumbs->add('ul');
            $ul->appendChild('<li><span class="icon-menus">เมนู</span></li>');
            $ul->appendChild('<li><span>'.$this->title().'</span></li>');
            $section->add('header', array(
                'innerHTML' => '<h1 class="icon-list">'.$this->title().'</h1>',
            ));
            // แสดงตาราง
            $section->appendChild(createClass('Index\Menus\View')->render($request));
            // คืนค่า

            return (object) array(
                'module' => 'menus',
                'title' => $this->title(),
                'detail' => $section->render(),
            );
        }
        // 404

        return \Index\PageNotFound\Controller::render();
    }

    /**
     * คืนค่าข้อความบนไตเติลบาร์เมื่อแสดงหน้านี้ ไปยัง Controller
     *
     * @return string
     */
    public function title()
    {
        return 'รายการเมนูที่สร้างแล้ว';
    }
}
