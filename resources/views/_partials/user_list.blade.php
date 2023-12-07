<table class="table table-hover">
    <thead>
        <tr>
            <th><a
                    href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_dir' => request('sort_dir') === 'asc' && request('sort_by') === 'name' ? 'desc' : 'asc']) }}">Name
                    @if (request('sort_by') === 'name')
                        @if (request('sort_dir') === 'asc')
                            <i class="bx bx-up-arrow-alt"></i>
                        @else
                            <i class="bx bx-down-arrow-alt"></i>
                        @endif
                    @endif
                </a>
            </th>
            <th><a
                    href="{{ request()->fullUrlWithQuery(['sort_by' => 'email', 'sort_dir' => request('sort_dir') === 'asc' && request('sort_by') === 'email' ? 'desc' : 'asc']) }}">Email
                    @if (request('sort_by') === 'email')
                        @if (request('sort_dir') === 'asc')
                            <i class="bx bx-up-arrow-alt"></i>
                        @else
                            <i class="bx bx-down-arrow-alt"></i>
                        @endif
                    @endif
                </a>
            </th>
            <th><a
                    href="{{ request()->fullUrlWithQuery(['sort_by' => 'gender', 'sort_dir' => request('sort_dir') === 'asc' && request('sort_by') === 'gender' ? 'desc' : 'asc']) }}">Gender
                    @if (request('sort_by') === 'gender')
                        @if (request('sort_dir') === 'asc')
                            <i class="bx bx-up-arrow-alt"></i>
                        @else
                            <i class="bx bx-down-arrow-alt"></i>
                        @endif
                    @endif
                </a>
            </th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">
        @foreach ($users as $user)
            <tr>
                <td>
                    <div class="d-flex justify-content-start align-items-center user-name">
                        <div class="avatar-wrapper">
                            <div class="avatar me-2">
                                @if ($user->profile == null)
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=41&background=696cff&color=FFFFFF"
                                        class="avatar-initial rounded-circle">
                                @else
                                    <img src="{{ asset('' . str_replace('/profile/', '/profile/thumbnail/', Auth::user()->profile) . '') }}"
                                        alt="Avatar" class="rounded-circle">
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column"><span class="emp_name text-truncate">{{ $user->name }}</span>
                        </div>
                    </div>
                </td>
                <td>{{ $user->email }}</td>
                <td><span class="badge bg-label-primary me-1">{{ $user->gender }}</span></td>
                <td>
                    <label class="switch switch-primary">
                        <input type="checkbox" name="status" data-id="{{ $user->id }}"
                            class="switch-input switch_is_active" {{ $user->is_active == true ? 'checked' : '' }} />
                        <span class="switch-toggle-slider">
                            <span class="switch-on">
                                <i class="bx bx-check"></i>
                            </span>
                            <span class="switch-off">
                                <i class="bx bx-x"></i>
                            </span>
                        </span>
                    </label>
                </td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                                class="bx bx-dots-vertical-rounded"></i></button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('user.edit', $user->id) }}"><i
                                    class="bx bx-edit-alt me-1"></i>
                                Edit</a>
                            <a class="dropdown-item delete" href="{{ route('user.destroy', $user->id) }}"><i
                                    class="bx bx-trash me-1"></i>
                                Delete</a>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{-- Pagination --}}
<div class="d-flex justify-content-center">
    {!! $users->links() !!}
</div>
