<div>
    <h1>Danh sách sinh viên</h1>

    <div id="form-area">
        <h3 id="form-title">Thêm sinh viên</h3>
        <form id="student-form">
            <input type="hidden" name="id" id="student-id" value="">
            <div>
                <label>Mã SV</label><br>
                <input type="text" name="code" id="code">
                <div class="error" data-field="code"></div>
            </div>
            <div>
                <label>Họ tên</label><br>
                <input type="text" name="full_name" id="full_name">
                <div class="error" data-field="full_name"></div>
            </div>
            <div>
                <label>Email</label><br>
                <input type="email" name="email" id="email">
                <div class="error" data-field="email"></div>
            </div>
            <div>
                <label>Ngày sinh</label><br>
                <input type="date" name="dob" id="dob">
                <div class="error" data-field="dob"></div>
            </div>
            <button type="submit" id="submit-btn">Lưu</button>
            <button type="button" id="cancel-edit" style="display:none">Huỷ</button>
        </form>
    </div>

    <hr>

    <table border="1" width="100%" id="students-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã SV</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Ngày sinh</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="students-body">
            <!-- rows inserted by JS -->
        </tbody>
    </table>

    <div id="no-data" style="display:none">Chưa có dữ liệu</div>
</div>