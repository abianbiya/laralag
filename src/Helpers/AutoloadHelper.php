<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

if (!function_exists('can')) {
    function can($route)
    {
        return Gate::allows($route);
    }
}

if (!function_exists('get')) {
    function get($what)
    {
        switch ($what) {
            case 'active_role':
                $here = session('active_role')['role'];
                break;
            case 'active_role_id':
                $here = session('active_role')['id'];
                break;

            default:
                $here = '';
                break;
        }

        return $here;
    }
}

// if (!function_exists('button')) {
//     function button($route, $title, $id = null, $class = "btn-primary")
//     {
//         $elm = explode('.', $route);
//         $action = end($elm);
//         $allowed = can($route);
//         if ($action == 'create') {
//             $button = $allowed ? '<a href="' . route($route, $id) . '" class="btn btn-sm icon icon-left float-end ' . $class . '"><i class="fa fa-plus"></i> Tambah ' . $title . '</a>'
//                 : '<button class="btn btn-sm icon icon-left float-end btn-secondary disabled"><i class="fa fa-plus"></i> Tambah ' . $title . '</button>';
//         } else if ($action == 'edit') {
//             $class = "btn-outline-primary";
//             $button = $allowed ? '<a href="' . route($route, $id) . '" class="btn btn-sm icon icon-left ' . $class . '"><i class="fa fa-pencil-alt"></i> Edit </a>'
//                 : '<button class="btn btn-sm icon icon-left btn-secondary disabled"><i class="fa fa-pencil-alt"></i> Edit </button>';
//         } else if ($action == 'destroy') {
//             $class = "btn-outline-danger";
//             $button = $allowed ? '<button onclick="deleteConfirm(\'' . route($route, $id) . '\')" class="btn btn-sm icon icon-left ' . $class . '"><i class="fa fa-trash"></i> Delete</button>'
//                 : '<button class="btn btn-sm icon icon-left btn-secondary disabled"><i class="fa fa-trash"></i> Delete </button>';
//         } else {
//             $class = "btn-outline-dark";
//             $button = $allowed ? '<a href="' . route($route, $id) . '" class="btn btn-sm icon icon-left ' . $class . '"><i class="fa fa-arrow-right"></i> ' . $title . ' </a>'
//                 : '<button class="btn btn-sm icon icon-left btn-secondary disabled"><i class="fa fa-arrow-right"></i> ' . $title . ' </button>';
//         }
//         return $button;
//     }
// }

if (!function_exists('actionButton')) {
    function actionButton($destination, $routeParams = [], $title = 'Data')
    {
        $button = '';
        if(str($destination)->endsWith(['.create']) && can($destination)){
            $button = html()->a(route($destination, $routeParams), '<i class="bi bi-plus-lg"></i>')->class('btn btn-outline-primary btn-sm float-end')
                        ->attributes(['data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top','title' => 'Tambah '.$title]);
        }elseif(str($destination)->endsWith(['.edit']) && can($destination)){
            $button = html()->a(route($destination, $routeParams), '<i class="bi bi-pencil"></i>')->class('btn btn-outline-warning btn-sm')
                        ->attributes(['data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top','title' => 'Edit '.$title]);
        }elseif(str($destination)->endsWith(['.show']) && can($destination)){
            $button = html()->a(route($destination, $routeParams), '<i class="bi bi-eye"></i>')->class('btn btn-outline-info btn-sm')
                        ->attributes(['data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top','title' => 'Lihat Detail '.$title]);
        }elseif(str($destination)->endsWith(['.destroy']) && can($destination)){
            $button = html()->button('<i class="bi bi-trash"></i>')->class('btn btn-outline-danger btn-sm')->id("wanna-swal")
                ->attributes([ 'data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'title' => 'Hapus ' . $title, 'data-sw-message' => 'Apakah anda yakin?', 'data-sw-href' => route($destination, $routeParams), 'data-sw-title' => 'Konfirmasi']);
        }
        return $button;
    }
}
if (!function_exists('customButton')) {
    function customButton($destination, $routeParams = null, $title = '', $icon = 'bi bi-info-circle', $class = 'btn-outline-info', $tooltip = null)
    {
        $button = '';
        if(can($destination)){
            $button = html()->a(route($destination, $routeParams), '<i class="'.$icon.'"></i> '.$title)->class('btn btn-sm '.$class);
            if(filled($tooltip)){
                $button = $button->attributes(['data-bs-toggle' => 'tooltip', 'data-bs-placement' => 'top', 'title' => $tooltip]);
            }
        }
        return $button;
    }
}

if (!function_exists('tanggal')) {
    function tanggal($when, $cetak_hari = false, $cetak_waktu = true)
    {
        return empty($when) ? '' : Carbon::parse($when)->translatedFormat('j F Y');
    }
}

if (!function_exists('rupiah')) {
    function rupiah($nominal)
    {
        return empty($nominal) ? '' : 'Rp ' . number_format($nominal, 0, ',', '.');
    }
}