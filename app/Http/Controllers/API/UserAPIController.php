<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateChangePasswordRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserAPIController
 */
class UserAPIController extends AppBaseController
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * @param  UserRepository  $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param  Request  $request
     * @return UserCollection
     */
    public function index(Request $request)
    {
        $perPage = getPageSize($request);
        $users = $this->userRepository->getUsers($perPage);
        UserResource::usingWithCollection();

        return new UserCollection($users);
    }

    /**
     * @param  CreateUserRequest  $request
     * @return UserResource
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();
        $user = $this->userRepository->storeUser($input);

        return new UserResource($user);
    }

    /**
     * @param $id
     * @return UserResource
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);

        return new UserResource($user);
    }

    /**
     * @param  UpdateUserRequest  $request
     * @param  User  $user
     * @return UserResource|JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if (Auth::id() == $user->id) {
            return $this->sendError('User can\'t be updated.');
        }
        $input = $request->all();
        $user = $this->userRepository->updateUser($input, $user->id);

        return new UserResource($user);
    }

    /**
     * @param  User  $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        if (Auth::id() == $user->id) {
            return $this->sendError('User can\'t be deleted.');
        }
        $this->userRepository->delete($user->id);

        return $this->sendSuccess('User deleted successfully');
    }

    /**
     * @return UserResource
     */
    public function editProfile()
    {
        $user = Auth::user();

        return new UserResource($user);
    }

    /**
     * @param  UpdateUserProfileRequest  $request
     * @return UserResource
     */
    public function updateProfile(UpdateUserProfileRequest $request)
    {
        $input = $request->all();
        $updateUser = $this->userRepository->updateUserProfile($input);

        return new UserResource($updateUser);
    }

    /**
     * @param  UpdateChangePasswordRequest  $request
     * @return JsonResponse
     */
    public function changePassword(UpdateChangePasswordRequest $request)
    {
        $input = $request->all();
        try {
            $this->userRepository->updatePassword($input);

            return $this->sendSuccess('Password updated successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function updateLanguage(Request $request): JsonResponse
    {
        $language = $request->get('language');
        $user = Auth::user();
        $user->update([
            'language' => $language,
        ]);

        return $this->sendResponse($user->language, 'Language Updated Successfully');
    }

    public function config(Request $request)
    {
        $user = Auth::user();

        $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();

        $composerFile = file_get_contents('../composer.json');
        $composerData = json_decode($composerFile, true);
        $currentVersion = isset($composerData['version']) ? $composerData['version'] : '';
        $dateFormat = getSettingValue('date_format');

        return $this->sendResponse([
            'permissions' => $userPermissions,
            'version' => $currentVersion,
            'date_format' => $dateFormat,
            'is_version' => getSettingValue('show_version_on_footer'),
            'is_currency_right' => getSettingValue('is_currency_right'),
        ], 'Config retrieved successfully.');
    }
}
