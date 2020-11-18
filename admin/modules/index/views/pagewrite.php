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
     * ฟอร์มแก้ไขหน้าเพจ
     *
     * @param Request $request
     * @param object $index
     * @return string
     */
    public function render(Request $request, $index)
    {
        // register form
        $form = Html::create('form', array(
            'id' => 'setup_frm',
            'class' => 'setup_frm',
            'autocomplete' => 'off',
            'action' => 'index.php/index/model/pages/save',
            'onsubmit' => 'doFormSubmit',
            'token' => true,
            'ajax' => true,
        ));
        $fieldset = $form->add('fieldset', array(
            'title' => 'รายละเอียดหน้าเพจ',
        ));
        // module
        $fieldset->add('text', array(
            'id' => 'write_module',
            'itemClass' => 'item',
            'labelClass' => 'g-input icon-documents',
            'label' => 'โมดูล',
            'comment' => 'ชื่อโมดูล ภาษาอังกฤษตัวพิมพ์เล็กเท่านั้น',
            'maxlength' => 20,
            'value' => isset($index['module']) ? $index['module'] : '',
        ));
        // topic
        $fieldset->add('text', array(
            'id' => 'write_topic',
            'itemClass' => 'item',
            'labelClass' => 'g-input icon-edit',
            'label' => 'หัวข้อ',
            'comment' => 'หัวข้อของหน้าเพจ แสดงบนไตเติลบาร์',
            'maxlength' => 255,
            'value' => isset($index['topic']) ? $index['topic'] : '',
        ));
        // detail
        $fieldset->add('ckeditor', array(
            'id' => 'write_detail',
            'itemClass' => 'item',
            'height' => 300,
            'language' => 'th',
            'toolbar' => 'Document',
            'upload' => true,
            'label' => 'รายละเอียด',
            'value' => isset($index['detail']) ? $index['detail'] : '',
        ));
        $fieldset = $form->add('fieldset', array(
            'class' => 'submit',
        ));
        // submit
        $fieldset->add('submit', array(
            'class' => 'button save large',
            'value' => 'บันทึก',
        ));
        $fieldset->add('hidden', array(
            'id' => 'write_id',
            'value' => $index['id'],
        ));
        $form->script('var WEB_URL = "'.WEB_URL.'vendor/goragod/kotchasan/";');

        return $form->render();
    }
}
