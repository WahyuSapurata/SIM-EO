<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataUser as RequestsDataUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DataUser extends BaseController
{
    public function index()
    {
        $module = 'Daftar User';
        return view('admin.datauser.index', compact('module'));
    }

    public function get()
    {
        // Mengambil semua data pengguna
        $dataFull = User::all();

        // Mengembalikan response berdasarkan data yang sudah disaring
        return $this->sendResponse($dataFull, 'Get data success');
    }

    public function store(RequestsDataUser $dataUser)
    {
        $data = array();
        try {
            $data = new User();
            $data->name = $dataUser->name;
            $data->username = $dataUser->username;
            $data->role = $dataUser->role;
            $data->lokasi = $dataUser->lokasi;
            $data->password = Hash::make('<>password');
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Added data success');
    }

    public function show($params)
    {
        $data = array();
        try {
            $data = User::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function update(RequestsDataUser $dataUser, $params)
    {
        try {
            $data = User::where('uuid', $params)->first();
            $data->name = $dataUser->name;
            $data->username = $dataUser->username;
            $data->role = $dataUser->role;
            $data->password = $dataUser->password ? $dataUser->password : $data->password;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }

    public function delete($params)
    {
        $data = array();
        try {
            $data = User::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
