# api เติมเกมดินน้ำมันอะชั่ยๆ | API TOPUP FREEFIRE
### วิธีใช้งาน
* ตั้งค่า Key Antirecaptcha ก่อน
* `$token_antirecaptcha = "";`
* เรียก class topup_freefire เพื่อใช้งานไง งงไรวะ
* `$topup = New topup_freefire($token_antirecaptcha);`
* แล้วก็ login freefire ด้วย uid ตัวนี้อะมันจะนานนิดหน่อยนะมันต้องถอดrecaptchaอะ
* `$topup->login_freefire($uid);`
* พอ login เสร็จ จะได้ open_id มาก็เอาไปเติมเงินได้เลยยย โดยที่ใช้บัตรการีน่านะ
* `$topup->topup_garenacard($open_id,$garenacard)`
## ไม่มีไรมากแต่ขออยากเอาไปขายพอเข้าใจตรงกันนะ
