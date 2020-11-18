<?php
/*
 * @filesource menu.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Menu;

/**
 * Description
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model
{

    /**
     * ข้อมูลรายการเมนู
     *
     * @return array
     */
    public static function get()
    {
        $result = array();
        if (is_file(ROOT_PATH.DATA_FOLDER.'menus.php')) {
            // โหลดรายการเมนู และวนลูปรายการเมนู
            foreach (include (ROOT_PATH.DATA_FOLDER.'menus.php') as $item) {
                // จัดรูปแบบข้อมูลเมนูให้เหมาะสม สำหรับการสร้างเมนู
                $result[$item['module']] = array(
                    'text' => $item['text'],
                    'target' => $item['target'],
                );
                if (empty($item['url'])) {
                    $result[$item['module']]['url'] = WEB_URL.'index.php?module='.$item['module'];
                } else {
                    $result[$item['module']]['url'] = $item['url'];
                }
            }
        }
        // คืนค่ารายการเมนูที่จัดรูปแบบแล้ว

        return $result;
    }
}
