<?php
/**
 * @filesource index/controllers/menuwrite.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */
namespace Index\Menuwrite;

use Kotchasan\Template;

/**
 * Description
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Kotchasan\View
{

    /**
     * ฟอร์มแก้ไขเมนู
     *
     * @param Request $request
     * @param object $index
     * @return string
     */
    public function render($index)
    {
        // create View
        $view = new static;
        // template
        $template = Template::load('', '', 'menuwrite');
        // target
        $targets = array('' => 'เปิดหน้าเดิม', '_blank' => 'เปิดหน้าใหม่');
        $target = array();
        foreach ($targets as $key => $value) {
            $sel = isset($index['target']) && $key == $index['target'] ? ' selected' : '';
            $target[] = '<option value="'.$key.'"'.$sel.'>'.$value.'</option>';
        }
        // แทนที่ข้อมูลลงในฟอร์ม
        $view->setContents(array(
            '/{MODULE}/' => isset($index['module']) ? $index['module'] : '',
            '/{TEXT}/' => isset($index['text']) ? $index['text'] : '',
            '/{URL}/' => isset($index['url']) ? $index['url'] : '',
            '/{TARGET}/' => implode('', $target),
            '/{ID}/' => $index['id'],
            '/{LASTID}/' => $index['last_id'],
            '/{TOKEN}/' => self::$request->createToken(),
        ));
        // คืนค่าเนื้อหา

        return $view->renderHTML($template);
    }
}
