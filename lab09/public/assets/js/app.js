$(function() {
    // debug: xác nhận file này được load và jQuery có sẵn
    console.log('app.js loaded');
    console.log('jQuery typeof $ =', typeof $ === 'function' ? 'function (ok)' : typeof $);

    // apiUrl tương đối, resolve từ /lab09/public/
    const apiUrl = 'index.php?c=student&a=api';

    console.log('apiUrl =', apiUrl);

    function showErrors(errors) {
        $('.error').text('');
        if (!errors) return;
        for (let field in errors) {
            $(`.error[data-field="${field}"]`).text(errors[field]);
        }
    }

    function resetForm() {
        $('#student-id').val('');
        $('#code').val('');
        $('#full_name').val('');
        $('#email').val('');
        $('#dob').val('');
        $('#form-title').text('Thêm sinh viên');
        $('#cancel-edit').hide();
        showErrors(null);
    }

    function renderRows(list) {
        const $body = $('#students-body').empty();
        if (!list || list.length === 0) {
            $('#no-data').show();
            $('#students-table').hide();
            return;
        }
        $('#no-data').hide();
        $('#students-table').show();
        list.forEach((s, idx) => {
            const dob = s.dob ? s.dob : '';
            const row = `
                <tr data-id="${s.id}">
                    <td class="stt">${idx+1}</td>
                    <td class="code">${escapeHtml(s.code)}</td>
                    <td class="full_name">${escapeHtml(s.full_name)}</td>
                    <td class="email">${escapeHtml(s.email)}</td>
                    <td class="dob">${escapeHtml(dob)}</td>
                    <td>
                        <button class="edit-btn">Sửa</button>
                        <button class="delete-btn">Xóa</button>
                    </td>
                </tr>
            `;
            $body.append(row);
        });
    }

    function loadList() {
        console.log('Calling API list...');
        $.get(apiUrl, {action: 'list'}).done(function(res) {
            console.log('API list response:', res);
            if (res.success) {
                renderRows(res.data);
            } else {
                alert(res.message || 'Lỗi khi tải danh sách');
            }
        }).fail(function(xhr) {
            console.error('Ajax error', xhr);
        });
    }

    // phần còn lại giữ nguyên (submit, edit, delete)
    $('#student-form').on('submit', function(e) {
        e.preventDefault();
        showErrors(null);
        const id = $('#student-id').val();
        const data = {
            action: id ? 'update' : 'create',
            id: id,
            code: $('#code').val().trim(),
            full_name: $('#full_name').val().trim(),
            email: $('#email').val().trim(),
            dob: $('#dob').val()
        };
        console.log('Submitting form', data);
        $.post(apiUrl, data).done(function(res) {
            console.log('Form submit response', res);
            if (res.success) {
                if (data.action === 'create') {
                    loadList();
                    resetForm();
                } else {
                    const $tr = $(`tr[data-id="${res.data.id}"]`);
                    $tr.find('.code').text(res.data.code);
                    $tr.find('.full_name').text(res.data.full_name);
                    $tr.find('.email').text(res.data.email);
                    $tr.find('.dob').text(res.data.dob || '');
                    resetForm();
                }
            } else {
                if (res.errors) {
                    showErrors(res.errors);
                } else {
                    alert(res.message || 'Lỗi server');
                }
            }
        }).fail(function(xhr) {
            console.error('Ajax error', xhr);
        });
    });

    $('#students-body').on('click', '.edit-btn', function() {
        const $tr = $(this).closest('tr');
        const id = $tr.data('id');
        $('#student-id').val(id);
        $('#code').val($tr.find('.code').text());
        $('#full_name').val($tr.find('.full_name').text());
        $('#email').val($tr.find('.email').text());
        $('#dob').val($tr.find('.dob').text());
        $('#form-title').text('Cập nhật sinh viên');
        $('#cancel-edit').show();
        $('html, body').animate({ scrollTop: 0 }, 'fast');
    });

    $('#cancel-edit').on('click', function() {
        resetForm();
    });

    $('#students-body').on('click', '.delete-btn', function() {
        if (!confirm('Bạn có muốn xóa bản ghi này không?')) return;
        const $tr = $(this).closest('tr');
        const id = $tr.data('id');
        $.post(apiUrl, {action: 'delete', id: id}).done(function(res) {
            console.log('Delete response', res);
            if (res.success) {
                $tr.remove();
                $('#students-body tr').each(function(i, el) {
                    $(el).find('.stt').text(i+1);
                });
                if ($('#students-body tr').length === 0) {
                    $('#no-data').show();
                    $('#students-table').hide();
                }
            } else {
                alert(res.message || 'Xóa thất bại');
            }
        }).fail(function(xhr) {
            console.error('Ajax error', xhr);
        });
    });

    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // initial load
    loadList();
});