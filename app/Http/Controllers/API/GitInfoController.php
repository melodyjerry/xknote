<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\GitInfoModel;

class GitInfoController extends Controller
{
    public function getConfig(Request $request)
    {
        $id = $request->user()->id;
        $git_info = GitInfoModel::where('uid', $id);
        if ($git_info->count() <= 0) {
            return response(
                [
                    'error' => true,
                    'message' => 'You need to set up Git information'
                ],
                404
            );
        }
        return $git_info->get()[0];
    }

    public function setConfig(Request $request)
    {
        $id = $request->user()->id;
        if (
            !$request->has('password') ||
            !$request->has('name') ||
            !$request->has('email')
        ) {
            return response(
                ['error' => 'Parameter not found. (password,name,email)'],
                400
            );
        }
        $config_m = GitInfoModel::where('uid', $id);
        $data = [
            'git_name' => $request->name,
            'git_email' => $request->email,
            'git_password' => $request->password
        ];
        if ($config_m->count() <= 0) {
            $config_m->create($data);
        } else {
            $config_m->update($data);
        }
        return ['error' => false];
    }
}
