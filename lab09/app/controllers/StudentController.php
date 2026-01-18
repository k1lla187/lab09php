<?php
require_once __DIR__ . '/../models/StudentModel.php';

class StudentController
{
    protected $model;

    public function __construct()
    {
        $this->model = new StudentModel();
    }

    // render view
    public function index()
    {
        // include layout which will include students/index.php
        require __DIR__ . '/../views/layout.php';
    }

    // API for Ajax: action=list/create/update/delete
    public function api()
    {
        header('Content-Type: application/json; charset=utf-8');
        $method = $_SERVER['REQUEST_METHOD'];

        $action = $_REQUEST['action'] ?? 'list';
        try {
            if ($action === 'list') {
                $data = $this->model->all();
                echo json_encode(['success' => true, 'data' => $data]);
                return;
            }

            // create
            if ($action === 'create' && $method === 'POST') {
                $input = [
                    'code' => trim($_POST['code'] ?? ''),
                    'full_name' => trim($_POST['full_name'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'dob' => trim($_POST['dob'] ?? '') ?: null,
                ];

                $errors = $this->validate($input);
                if ($this->model->existsByCode($input['code'])) {
                    $errors['code'] = 'Mã SV đã tồn tại';
                }
                if ($this->model->existsByEmail($input['email'])) {
                    $errors['email'] = 'Email đã tồn tại';
                }

                if (!empty($errors)) {
                    echo json_encode(['success' => false, 'errors' => $errors]);
                    return;
                }

                $student = $this->model->create($input);
                echo json_encode(['success' => true, 'message' => 'Thêm thành công', 'data' => $student]);
                return;
            }

            // update
            if ($action === 'update' && $method === 'POST') {
                $id = intval($_POST['id'] ?? 0);
                if (!$id || !$this->model->find($id)) {
                    echo json_encode(['success' => false, 'message' => 'ID không tồn tại']);
                    return;
                }

                $input = [
                    'code' => trim($_POST['code'] ?? ''),
                    'full_name' => trim($_POST['full_name'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'dob' => trim($_POST['dob'] ?? '') ?: null,
                ];

                $errors = $this->validate($input);
                if ($this->model->existsByCode($input['code'], $id)) {
                    $errors['code'] = 'Mã SV đã tồn tại';
                }
                if ($this->model->existsByEmail($input['email'], $id)) {
                    $errors['email'] = 'Email đã tồn tại';
                }

                if (!empty($errors)) {
                    echo json_encode(['success' => false, 'errors' => $errors]);
                    return;
                }

                $student = $this->model->update($id, $input);
                echo json_encode(['success' => true, 'message' => 'Cập nhật thành công', 'data' => $student]);
                return;
            }

            // delete
            if ($action === 'delete' && $method === 'POST') {
                $id = intval($_POST['id'] ?? 0);
                if (!$id || !$this->model->find($id)) {
                    echo json_encode(['success' => false, 'message' => 'ID không tồn tại']);
                    return;
                }
                $this->model->delete($id);
                echo json_encode(['success' => true, 'message' => 'Xóa thành công']);
                return;
            }

            echo json_encode(['success' => false, 'message' => 'Action không hợp lệ']);
        } catch (Exception $e) {
            // log server-side; for demo we return message but in production log and return generic
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Server error: '.$e->getMessage()]);
        }
    }

    protected function validate($input)
    {
        $errors = [];
        if ($input['code'] === '') $errors['code'] = 'Mã SV bắt buộc';
        if ($input['full_name'] === '') $errors['full_name'] = 'Họ tên bắt buộc';
        if ($input['email'] === '') {
            $errors['email'] = 'Email bắt buộc';
        } elseif (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }
        // dob optional; could validate date format if provided
        return $errors;
    }
}