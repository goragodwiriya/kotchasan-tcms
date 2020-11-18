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

use Kotchasan\Http\Request;
use Kotchasan\Login;

/**
 * ตารางเมนู
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model
{

    /**
     * อ่านรายการเมนูที่ ID สำหรับการแก้ไข
     * หรือ อ่าน ID ถัดไป ของเมนู สำหรับการสร้างเมนูใหม่
     *
     * @param int $id ID ของรายการที่ต้องการ
     * @return array|boolean คืนค่ารายการที่พบ, ไม่พบคืนค่า false
     */
    public static function get($id)
    {
        // อ่านรายการเมนูทั้งหมด
        $menus = self::all();
        if ($id > 0) {
            // ตรวจสอบรายการที่แก้ไข
            if (isset($menus[$id])) {
                $index = $menus[$id];
                $index['last_id'] = $id;
            } else {
                $index = false;
            }
        } else {
            // ใหม่
            $index = array(
                'id' => sizeof($menus) + 1,
                'last_id' => 0,
            );
        }

        return $index;
    }

    /**
     * อ่านรายการเมนูทั้งหมด
     *
     * @return array
     */
    public static function all()
    {
        $datas = array();
        if (is_file(ROOT_PATH.DATA_FOLDER.'menus.php')) {
            foreach (include (ROOT_PATH.DATA_FOLDER.'menus.php') as $item) {
                $datas[$item['id']] = $item;
            }
        }

        return $datas;
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
                $save = array();
                $n = 1;
                foreach (self::all() as $item) {
                    if (!in_array($item['id'], $ids)) {
                        $item['id'] = $n;
                        $save[$item['module']] = $item;
                        $n++;
                    }
                }
                self::saveToFile($save);
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
                'id' => $request->post('write_id')->toInt(),
                'module' => $request->post('write_module')->username(),
                'text' => $request->post('write_text')->topic(),
                'url' => $request->post('write_url')->url(),
                'target' => $request->post('write_target')->topic(),
            );
            // รายการที่แก้ไข 0 รายการใหม่
            $id = $request->post('last_id')->toInt();
            // โหลดรายการเมนูทั้งหมด
            $menus = self::all();
            // ตรวจสอบค่าที่ส่งมา
            $ret = array();
            if ($id > 0 && !isset($menus[$id])) {
                $ret['alert'] = 'ไม่พบข้อมูลที่แก้ไข กรุณารีเฟรช';
            } elseif ($save['module'] == '') {
                $ret['alert'] = 'กรุณากรอก โมดูล';
                $ret['input'] = 'write_module';
            } elseif ($save['text'] == '') {
                $ret['alert'] = 'กรุณากรอก ข้อความบนเมนู ';
                $ret['input'] = 'write_text';
            } else {
                // ปรับปรุงรายการเมนู
                $menus = self::updateMenu($menus, $id, $save);
                // บันทึก
                if (self::saveToFile($menus)) {
                    // เคลียร์ Token
                    $request->removeToken();
                    // คืนค่า
                    $ret['alert'] = 'บันทึกเรียบร้อย';
                    $ret['location'] = 'index.php?module=menus';
                } else {
                    $ret['alert'] = 'ไฟล์ '.DATA_FOLDER.'menus.php ไม่สามารถเขียนได้';
                }
            }
            // คืนค่าเป็น JSON
            echo json_encode($ret);
        }
    }

    /**
     * อัปเดตรายการเมนู
     *
     * @param array $menus รายการเมนู
     * @param int $id ID ของรายการที่แก้ไข 0 หมายถึงรายการใหม่
     * @param array $save ข้อมูลเมนู
     * @return array
     */
    private static function updateMenu($menus, $id, $save)
    {
        $result = array();
        $n = 1;
        foreach ($menus as $key => $item) {
            // ตรงกับ ID ที่ต้องการ เก็บรายการใหม่
            if ($n == $save['id']) {
                $result[$save['module']] = $save;
                $n++;
            }
            // รายการใหม่ หรือ ไม่ใช่รายการที่แก้ไข เก็บรายการเดิม
            if ($id == 0 || $key != $id) {
                $item['id'] = $n;
                $result[$item['module']] = $item;
                $n++;
            }
        }
        // รายการใหม่ที่เป็นรายการสุดท้าย
        if ($save['id'] >= $n) {
            $item['id'] = $n;
            $result[$save['module']] = $save;
        }

        return $result;
    }

    /**
     * บันทึกลงไฟล์
     *
     * @param array $save
     * @return boolean true ถ้าสำเร็จ
     */
    private static function saveToFile($save)
    {
        $f = @fopen(ROOT_PATH.DATA_FOLDER.'menus.php', 'w');
        if ($f) {
            fwrite($f, "<?php\nreturn ".var_export($save, true).';');
            fclose($f);

            return true;
        }

        return false;
    }
}
