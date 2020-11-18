<?php
/**
 * @filesource index/controllers/pages.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Pages;

use Kotchasan\File;
use Kotchasan\Http\Request;
use Kotchasan\Login;

/**
 * ตารางหน้าเพจ
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model
{

    /**
     * อ่านรายการโมดูลที่ติดตั้งแล้ว
     *
     * @param int $id -1 คืนค่าทุกรายการ, 0 สำหรับการสร้างหน้าเพจใหม่, มากกว่า 0 ค้นหารายการที่ต้องการ
     * @return array คืนค่ารายการที่พบ, ไม่พบคืนค่าแอเรย์ว่าง
     */
    public static function get($id)
    {
        if ($id === 0) {
            // ใหม่
            return array(
                'id' => 0,
            );
        } else {
            $datas = array();
            foreach (glob(ROOT_PATH.DATA_FOLDER.'index/*.php') as $item) {
                $page = include $item;
                if ($id === -1) {
                    // คืนค่าทุกรายการ
                    $datas[] = $page;
                } elseif ($page['id'] == $id) {
                    // คืนค่ารายการที่ต้องการ (แก้ไข)
                    return $page;
                }
            }

            return $datas;
        }
    }

    /**
     * ตรวจสอบโมดูลซ้ำ
     *
     * @param string $module ชื่อโมดูล
     * @param string $id ID ของโมดูล
     * @return boolean true ถ้าโมดูลซ้ำ
     */
    public static function exists($module, $id)
    {
        $file = ROOT_PATH.DATA_FOLDER.'index/'.$module.'.php';
        if (is_file($file)) {
            if ($id == 0) {
                // รายการใหม่แต่มีโมดูล
                return true;
            } else {
                // แก้ไข แต่ ID ไม่ตรงกัน
                $page = include $file;
                if ($page['id'] != $id) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * รับค่าจาก action ของตาราง
     *
     * @param Request $request
     */
    public function action(Request $request)
    {
        // session, referer, member
        if ($request->initSession() && $request->isReferer() && Login::isMember()) {
            $action = $request->post('action')->toString();
            if ($action == 'delete') {
                // รับค่า id แยกออกเป็นแอเรย์และแปลงให้เป็นตัวเลข
                $ids = explode(',', $request->post('id')->filter('\d,'));
                foreach (self::get() as $page) {
                    if (in_array($page['id'], $ids)) {
                        @unlink(ROOT_PATH.DATA_FOLDER.'index/'.$page['module'].'.php');
                    }
                }
            }
        }
    }

    /**
     * รับค่าจาก form
     *
     * @param Request $request
     */
    public function save(Request $request)
    {
        // session, token, member
        if ($request->initSession() && $request->isSafe() && Login::isMember()) {
            // รับค่าจากการ POST
            $save = array(
                'id' => time(),
                'module' => $request->post('write_module')->username(),
                'topic' => $request->post('write_topic')->topic(),
                'detail' => $request->post('write_detail')->detail(),
            );
            // รายการที่แก้ไข 0 รายการใหม่
            $id = $request->post('write_id')->toInt();
            // ตรวจสอบค่าที่ส่งมา
            $ret = array();
            if ($id > 0) {
                // ตรวจสอบรายการที่แก้ไข
                $index = self::get($id);
            }
            if ($id > 0 && empty($index)) {
                $ret['alert'] = 'ไม่พบข้อมูลที่แก้ไข กรุณารีเฟรช';
            } elseif ($save['module'] == '') {
                $ret['alert'] = 'กรุณากรอก โมดูล';
                $ret['input'] = 'write_module';
            } elseif ($save['topic'] == '') {
                $ret['alert'] = 'กรุณากรอก หัวข้อ';
                $ret['input'] = 'write_topic';
            } elseif (!File::makeDirectory(ROOT_PATH.DATA_FOLDER.'index/')) {
                $ret['alert'] = 'ไดเร็คทอรี่ '.DATA_FOLDER.' ไม่สามารถเขียนได้';
            } else {
                // ตรวจสอบโมดูลซ้ำ
                if (self::exists($save['module'], $id)) {
                    $ret['alert'] = 'มีโมดูลนี้อยู่ก่อนแล้ว';
                } else {
                    $f = @fopen(ROOT_PATH.DATA_FOLDER.'index/'.$save['module'].'.php', 'w');
                    if ($f) {
                        fwrite($f, "<?php\nreturn ".var_export($save, true).';');
                        fclose($f);
                        // เคลียร์ Token
                        $request->removeToken();
                        // คืนค่า
                        $ret['alert'] = 'บันทึกเรียบร้อย';
                        $ret['location'] = 'index.php?module=pages';
                    } else {
                        $ret['alert'] = 'ไดเร็คทอรี่ '.DATA_FOLDER.'index/ ไม่สามารถเขียนได้';
                    }
                }
            }
            // คืนค่าเป็น JSON
            echo json_encode($ret);
        }
    }
}
