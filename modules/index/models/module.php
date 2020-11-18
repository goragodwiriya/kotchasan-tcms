<?php
/*
 * @filesource module.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Module;

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
     * อ่านข้อมูลโมดูลที่เลือก
     *
     * @param string $module
     * @return array|bool คืนค่าผลลัพท์ที่พบเพียงรายการเดียว ไม่พบข้อมูลคืนค่า false
     */
    public static function get($module)
    {
        $file = ROOT_PATH.DATA_FOLDER.'index/'.$module.'.php';
        if (file_exists($file)) {
            return include $file;
        }

        return false;
    }
}
