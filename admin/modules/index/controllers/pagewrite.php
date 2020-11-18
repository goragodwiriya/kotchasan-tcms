<?php
/**
 * @filesource index/controllers/pagewrite.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Pagewrite;

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
            // ข้อมูลที่ต้องการ
            $index = \Index\Pages\Model::get($request->get('id')->toInt());
            if ($index) {
                // แสดงผล
                $section = Html::create('section');
                // breadcrumbs
                $breadcrumbs = $section->add('div', array(
                    'class' => 'breadcrumbs',
                ));
                $ul = $breadcrumbs->add('ul');
                $ul->appendChild('<li><span class="icon-documents">หน้าเพจ</span></li>');
                $ul->appendChild('<li><span>'.$this->title().'</span></li>');
                $section->add('header', array(
                    'innerHTML' => '<h1 class="icon-write">'.$this->title().'</h1>',
                ));
                // แสดงตาราง
                $section->appendChild(createClass('Index\Pagewrite\View')->render($request, $index));
                // คืนค่า

                return (object) array(
                    'module' => 'pages',
                    'title' => $this->title(),
                    'detail' => $section->render(),
                );
            }
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
        return 'สร้าง-แก้ไข หน้าเพจ';
    }
}
