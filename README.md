# Lab09 — Quản lý sinh viên (PDO + MVC + jQuery Ajax)

Mô tả ngắn:
Project demo "Quản lý sinh viên" dùng PDO (utf8mb4, ERRMODE_EXCEPTION), tổ chức MVC tối giản, API trả JSON và thao tác Create/Read/Update/Delete bằng jQuery Ajax (không reload trang).

Yêu cầu môi trường
- Apache + PHP 8.x + MySQL (XAMPP / Laragon).
- Trình duyệt: Chrome/Edge (DevTools).
- PHP extensions: pdo, pdo_mysql.

Cấu trúc thư mục (tóm tắt)
```
lab09/
├─ app/
│  ├─ config/db.php
│  ├─ core/Database.php
│  ├─ controllers/StudentController.php
│  ├─ models/StudentModel.php
│  └─ views/
│     ├─ layout.php
│     └─ students/index.php
├─ public/
│  ├─ index.php
│  ├─ test_db.php
│  ├─ debug_db.php (tùy chọn)
│  └─ assets/
│     ├─ js/app.js
│     └─ css/style.css
├─ database/it3220_php.sql
└─ README.md, README.txt, evidence.docx
```

Hướng dẫn cài đặt & chạy (Windows + XAMPP)
1. Sao chép project vào webroot:
   - Ví dụ: `C:\xampp\htdocs\lab09`

2. Start Apache và MySQL (XAMPP Control Panel).

3. Import database:
   - Mở phpMyAdmin (http://localhost/phpmyadmin)
   - Import file `database/it3220_php.sql`
   - Hoặc chạy SQL sau trong phpMyAdmin → SQL:
```sql
CREATE DATABASE IF NOT EXISTS it3220_php CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE it3220_php;
CREATE TABLE IF NOT EXISTS students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(20) NOT NULL UNIQUE,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  dob DATE NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO students (code, full_name, email, dob) VALUES
('SV001','Nguyen Van A','a@example.com','1999-01-01'),
('SV002','Tran Thi B','b@example.com','2000-02-02'),
('SV003','Le Van C','c@example.com','1998-03-03'),
('SV004','Pham Thi D','d@example.com','1997-04-04'),
('SV005','Hoang Van E','e@example.com','1996-05-05');
```

4. Cấu hình kết nối database:
   - Mở `app/config/db.php` và sửa thông tin nếu cần:
```php
return [
  'host' => '127.0.0.1',
  'dbname' => 'it3220_php',
  'user' => 'root',
  'pass' => '', // XAMPP mặc định rỗng
  'charset' => 'utf8mb4',
];
```

5. Truy cập ứng dụng:
   - Nếu đặt project ở `C:\xampp\htdocs\lab09`:
     - Test DB: `http://localhost/lab09/public/test_db.php`
     - Ứng dụng: `http://localhost/lab09/public/`

Lưu ý về đường dẫn (important)
- Nếu bạn truy cập ứng dụng qua đường dẫn con `/lab09/public/`, các đường dẫn tới assets và API trong `layout.php` và `app.js` đã dùng đường dẫn tương đối (không bắt đầu bằng `/`) để hoạt động khi không đặt DocumentRoot trỏ trực tiếp tới `public/`.
- Nếu cấu hình Virtual Host và DocumentRoot trỏ tới `lab09/public`, bạn có thể đổi đường dẫn assets thành dạng `/assets/...` tuỳ thích.

Kiểm thử chức năng
- List: trang chính sẽ gọi API `index.php?c=student&a=api&action=list` và render bảng.
- Create: form submit gửi POST `action=create` → API trả `{success:true, data:...}` → JS thêm dòng mới vào DOM.
- Update: submit `action=update` với `id` → API trả updated record → JS cập nhật row.
- Delete: POST `action=delete` với `id` → API trả success → JS xóa DOM row.

Các endpoint chính (API)
- GET/POST `index.php?c=student&a=api&action=list`
- POST `index.php?c=student&a=api&action=create`
- POST `index.php?c=student&a=api&action=update`
- POST `index.php?c=student&a=api&action=delete`

Debug nhanh
- Nếu DB lỗi: mở `http://localhost/lab09/public/test_db.php`
- Nếu API có dữ liệu nhưng trang không hiển thị:
  - Mở DevTools (F12) → Console và Network → kiểm tra:
    - app.js đã load không? (Network 200)
    - Có request XHR tới API không?
    - Console có lỗi `Uncaught ReferenceError: $ is not defined` → jQuery chưa load trước app.js
  - Hard reload (Ctrl+F5) để clear cache.

Yêu cầu nộp (theo đề)
- Nộp file zip `lab09.zip` chứa toàn bộ project + folder `database/it3220_php.sql`.
- Kèm `README.txt` (plain text) mô tả cách chạy.
- Kèm `evidence.docx` (3 ảnh minh chứng):
  1. Test DB OK (`01_test_db.png`)
  2. List hiển thị (`02_list.png`)
  3. Ajax create/update/delete thành công (`03_ajax.png`)

Mẹo chụp ảnh minh chứng
- Mở DevTools → Network → filter XHR → tick "Preserve log".
- Khi thực hiện Create/Update/Delete: chọn request tương ứng → xem tab Headers (Request Payload) và tab Response (JSON).
- Chụp màn hình bao gồm cả URL bar, phần giao diện và phần DevTools (Request + Response) để giám khảo kiểm chứng.

File chính cần chú ý (để chấm)
- `app/core/Database.php` — kết nối PDO (charset utf8mb4, ERRMODE_EXCEPTION)
- `app/models/StudentModel.php` — all(), find(), create(), update(), delete(), prepared statements
- `app/controllers/StudentController.php` — action index(), api()
- `public/assets/js/app.js` — jQuery Ajax (list/create/update/delete)
- `app/views/layout.php` và `app/views/students/index.php` — view + form

Checklist kiểm tra trước khi nộp
- [ ] Import SQL thành công (it3220_php)
- [ ] test_db.php hiển thị kết nối OK
- [ ] List hiển thị dữ liệu trên UI
- [ ] Create/Update/Delete hoạt động qua Ajax (không reload)
- [ ] evidence.docx có 3 ảnh đúng yêu cầu
- [ ] README.txt / README.md có hướng dẫn chạy
