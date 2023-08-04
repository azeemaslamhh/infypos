<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleAPIController extends AppBaseController
{
    /**
     * @var RoleRepository
     */
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param  Request  $request
     * @return RoleCollection
     */
    public function index(Request $request)
    {
        $perPage = getPageSize($request);
        $roles = $this->roleRepository->paginate($perPage);
        RoleResource::usingWithCollection();

        return new RoleCollection($roles);
    }

    /**
     * @param  CreateRoleRequest  $request
     * @return RoleResource
     */
    public function store(CreateRoleRequest $request)
    {
        $input = $request->all();
        $role = $this->roleRepository->storeRole($input);

        return new RoleResource($role);
    }

    /**
     * @param  Role  $role
     * @return RoleResource
     */
    public function show(Role $role)
    {
        return new RoleResource($role);
    }

    /**
     * @param  UpdateRoleRequest  $request
     * @param  Role  $role
     * @return RoleResource|JsonResponse
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        if ($role->name == Role::ADMIN) {
            return $this->sendError('Admin role Can\'t be updated.');
        }

        $input = $request->all();
        $role = $this->roleRepository->updateRole($input, $role->id);

        return new RoleResource($role);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        /** @var Role $role */
        $role = Role::findorFail($id);
        if ($role->users->count()) {
            return $this->sendError($role->display_name.' role can\'t be deleted.');
        }
        $role->delete();

        return $this->sendSuccess('Role deleted successfully');
    }
}
