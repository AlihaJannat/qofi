{{ csrf_field() }}
<div class="row">
    <div class="col-12 mb-4">
        <div class="form-group">
            <label class="form-label">Name*</label>
            <input required type="text" name="name" class="form-control" value="{{ $role->name }}" readonly />
            <input type="hidden" name="role_id" value="{{ $role->id }}">
        </div>
    </div>
    <div class="col-12">
        <h5>Role Permissions</h5>
        <!-- Permission table -->
        <div class="table-responsive">
            <table class="table table-flush-spacing">
                <tbody>
                    <tr>
                        <td class="text-nowrap">Administrator Access <i class="bx bx-info-circle bx-xs"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                aria-label="Allows a full access to the system"
                                data-bs-original-title="Allows a full access to the system"></i>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" onclick="reflectAll(this)" type="checkbox"
                                    id="selectAll">
                                <label class="form-check-label" for="selectAll">
                                    Select All
                                </label>
                            </div>
                        </td>
                    </tr>
                    @foreach ($permissions as $key => $permission_module)
                        <tr>
                            <td class="text-nowrap text-capitalize">{{ $key }}</td>
                            @foreach ($permission_module as $permission)
                                <td>
                                    <div class="d-flex">
                                        <div class="form-check me-3 me-lg-5">
                                            <input class="form-check-input" type="checkbox"
                                                onclick="permissionCheck(this, '{{ $permission->name }}');"
                                                value="{{ $permission->id }}" data-permission="{{ $permission->name }}"
                                                name="permissions[]"
                                                {{ $role->permissions->contains('name', $permission->name) ? 'checked' : '' }}
                                                id="{{ $permission->id }}-edit">
                                            <label class="form-check-label text-capitalize" for="{{ $permission->id }}-edit">
                                                {{ str_replace('_', ' ', $permission->name) }}?
                                            </label>
                                        </div>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Permission table -->
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-primary">Update</button>
    <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
</div>
