<?php
namespace App\ServiceObjects;

use Auth;
use \Config;
use App\User;
use Carbon\Carbon;
use App\Utils\Utils;
use App\Color;
use Illuminate\Support\Facades\Storage;
use App\Mail\EmailUpdate;
use Mail;

class UpdateProfileServiceObject
{
    private $utils;
    private $user;
    private $file;
    private $color;

    public function __construct(Utils $utils, User $user, Storage $storage, Color $color)
    {
        $this->utils = $utils;
        $this->user = $user;
        $this->storage = $storage;
        $this->color = $color;
    }

    public function saveProfile($id, $request, $profile_image, $profile_banner)
    {
        try {
            $user = Auth::user();
            $newmail = $request->email;
            $this->user->updateUserDetails($id, $request, $profile_image, $profile_banner,  Carbon::now());
            if ($user->email != $newmail) {
                $this->sendEmailUpdate($user, $newmail);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function sendEmailUpdate($user, $newmail)
    {
        Mail::to($newmail)->send(new EmailUpdate($user, $newmail));
    }

    public function updateProfile($request)
    {
        try {
            $profile_image = $this->updateImage(
                Auth::user()->profile_image,
                $request->profile_image,
                Config::get('constants.avatar_width'),
                Config::get('constants.avatar_height'),
                Config::get('constants.avatars')
            );
            $profile_banner = $this->updateImage(
                Auth::user()->profile_banner,
                $request->profile_banner,
                Config::get('constants.banner_width'),
                Config::get('constants.banner_height'),
                Config::get('constants.banners'),
                'scale-down'
            );
            $this->saveProfile(
                Auth::user()->id,
                $request,
                $profile_image,
                $profile_banner
            );
            $this->updateColor($request->color);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return array(
            'user' => $this->user->find(Auth::user()->id),
            'color' => $request->color == "" ? 'default':$request->color,
        );
    }

    public function updateColor($color)
    {
        if($color != '') {
            $this->color->where('user_id', Auth::user()->id)->update(['color' => $color]);
        }
    }

    public function updateImage($profile_image, $new_image, $width, $height, $location, $option='fit')
    {
        $image_name = $profile_image;
        if ($new_image) {
            try {
                $image_name = $this->utils->fitAndSaveImage(Auth::id(), $new_image, $width, $height, $location, $option);
                if ($profile_image != 'placeholder.jpg' and $profile_image != 'banner.jpg') {
                    \File::delete($location.'/'.$profile_image);
                    \File::delete($location.'/original_'.$profile_image);
                }
            } catch (Exception $e) {
                throw new Exception("Unable To Update Profile Image");
            }
        }
        return $image_name;
    }
}
