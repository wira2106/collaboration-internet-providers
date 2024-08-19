<?php

namespace Modules\User\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Contracts\Authentication;
use Modules\User\Repositories\UserRepository;
use Modules\User\Transformers\UserProfileTransformer;
use Modules\Utils\Http\Controllers\ImageController;

class ProfileController extends Controller
{
    /**
     * @var Authentication
     */
    private $auth;
    /**
     * @var UserRepository
     */
    private $user;

    public function __construct(Authentication $auth, UserRepository $user)
    {
        $this->auth = $auth;
        $this->user = $user;
    }

    public function findCurrentUser()
    {
        return new UserProfileTransformer($this->auth->user());
    }

    public function update(Request $request)
{
        $user = $this->auth->user();
        $data = $request->all();
        if ($request->file('photo_profile') != null && $request->file('photo_profile') != "") {
            $filename = ImageController::uploadFoto($request->file('photo_profile'));
            $data["photo_profile"] = $filename;
        } else {
            unset($data["photo_profile"]);
        }

        $this->user->update($user, $data);
        $this->auth->user()->createLog(
            trans('user::users.logs.edit.tipe'), 
            trans('user::users.logs.edit.description', [
                'data' => $request->full_name
            ])
        );
        return response()->json([
            'errors' => false,
            'message' => trans('user::messages.profile updated'),
        ]);
    }
}
