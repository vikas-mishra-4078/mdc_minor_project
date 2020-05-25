<?php

function userDetails($id)
{
    return User::where('id', $id)->first();
}

function formatedDate($date, $formate = 'h:i a, d F, Y')
{   
    return date($formate, strtotime($date));
}

function createDirectory($baseDir, $subDir, $permission=0777)
{
    $directory = public_path('uploads/'.$baseDir.'/'.$subDir.'/');

    if (!file_exists($directory)) {
        mkdir($directory, $permission, true);
    }
}

function createUserImageDirectories($id)
{
    createDirectory('users', $id);
    createDirectory('users', $id.'/thumb');
    createDirectory('users', $id.'/medium');
}

function createUserDocsDirectories($id) 
{
    createDirectory('users', $id.'/docs');
}

function getImage($image, $directory, array $options = null)
{
    return config('filesystems.disks.uploads.url') . '/'  . $directory . '/' . $image;
}

function unlinkOldImages($image, $directory) {
    // unlink old image
    @unlink(config('filesystems.disks.uploads.root') . '/'  . $directory . '/' . $image);
    @unlink(config('filesystems.disks.uploads.root') . '/'  . $directory . '/thumb/' . $image);
    @unlink(config('filesystems.disks.uploads.root') . '/'  . $directory . '/medium/' . $image);
}

function getStatus($status) {

    $statusList = ['Inactive', 'Active'];

    return $statusList[$status];
}

function getVerificationStatus($status) {
    
    $statusList = ['Un-Verified', 'Verified'];

    return $statusList[$status];
}

function getRoleBasedImagePrefix() {

    $all = [
        'Admin' => 'admin_',
        'User' => 'user_',
    ];

    return $all[Auth::user()->role];
}

function getRoleBasedImageDirectory() {
    
    $all = [
        'Admin' => 'users',
        'User' => 'users',
    ];

    return $all[Auth::user()->role];
}

function createRoleBasedImageDirectories($id) {

    if(Auth::user()->role == 'Admin') {
        createUserImageDirectories($id);
    }
    else if(Auth::user()->role == 'User') {
        createUserImageDirectories($id);
    }
}
