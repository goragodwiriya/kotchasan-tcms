<?php
/*
 * load.php
 *
 * @author Goragod Wiriya <admin@goragod.com>
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */
/*
 * Site root
 */
define('ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)).'/');
/*
 * ที่เก็บไฟล์
 */
define('DATA_FOLDER', 'datas/');
/*
 * 0 (default) บันทึกเฉพาะข้อผิดพลาดร้ายแรงลง error_log .php
 * 1 บันทึกข้อผิดพลาดและคำเตือนลง error_log .php
 * 2 แสดงผลข้อผิดพลาดและคำเตือนออกทางหน้าจอ (ใช้เฉพาะตอนออกแบบเท่านั้น)
 */
define('DEBUG', 2);
// load Kotchasan
include 'vendor/goragod/kotchasan/Kotchasan/load.php';
