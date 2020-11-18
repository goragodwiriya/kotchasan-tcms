/**
 * ฟังก์ชั่นรับค่าจากการ submit ด้วย Ajax จาก Form
 *
 * @param Object xhr Object XHR จาก Ajax
 */
function doFormSubmit(xhr) {
  var ds = xhr.responseText.toJSON();
  if (ds) {
    for (var prop in ds) {
      var val = ds[prop];
      if (prop == 'alert') {
        alert(val);
      }
      if (prop == 'location') {
        window.location = val.replace(/&amp;/g, '&');
      }
      if (prop == 'input') {
        el = $G(val);
        el.invalid();
        el.focus();
      }
    }
  } else if (xhr.responseText != '') {
    alert(xhr.responseText);
  }
}

/**
 * ฟังก์ชั่นส่งค่าด้วย Ajax
 *
 * @param string target URL ปลายทางที่ต้องการเรียก
 * @param string query พารามิเตอร์ที่ต้องการส่งไป
 * @param function callback ฟังก์ชั่นสำหรับรับค่ากลับ
 * @param string wait id ของรูปรอโหลด
 * @param boolean c true แสดงรูปรอโหลดกลางจอภาพ
 */
function send(target, query, callback, wait, c) {
  var req = new GAjax();
  req.initLoading(wait || 'wait', false, c);
  req.send(target, query, function (xhr) {
    callback.call(this, xhr);
  });
}
