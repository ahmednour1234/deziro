@if (count($attributes))
    <nav class="nav-pagination mx-3" aria-label="Page navigation">
        <div class="row">
            <div class="label col-lg-10 col-md-6">
                <span>Showing {{ $attributes->firstItem() }} to {{ $attributes->lastItem() }}
                    of total {{ $attributes->total() }} entries</span>
            </div>
        </div>
        <ul class="pagination  mb-0">
            <li>
                <div class="btn-group">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">{{ currentLimit() }}</button>
                    <ul class="dropdown-menu" style="min-width: auto;">
                        @foreach (limits() as $limit)
                            <li><a class="dropdown-item {{ $limit['active'] ? 'active' : '' }}"
                                    href="{{ $limit['url'] }}">{{ $limit['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
@endif


<table class="table" id="table_id" style="width: 100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Name</th>
            <th>Type</th>
            <th>Created AT</th>
            <th>Action</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($attributes as $attribute)
            <tr id="attribute_{{ $attribute->id }}">

                <td>{{ $attribute->id }}</td>
                <td>{{ $attribute->code }} </td>
                <td>{{ $attribute->name }} </td>
                <td>{{ Str::ucfirst($attribute->type) }} </td>
                <td>{{ $attribute->created_at }} </td>
                <td>
                    <a href="{{ route('admin.attributes.edit', $attribute->id) }}">
                        <button type="button" class="btn btn-primary btn-sm">
                            <i class="fa fa-edit"></i>
                        </button>
                    </a>
                    <button type="button" id="delete_attribute" class="btn btn-danger btn-sm"
                        data-id="{{ $attribute->id }}" data-name="{{ $attribute->name }}">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@if ($attributes->hasMorePages())
    <div class="row mt-3">
        <div class="col-lg-8 mx-2">
            {{ $attributes->links() }}
        </div>
    </div>
@endif
