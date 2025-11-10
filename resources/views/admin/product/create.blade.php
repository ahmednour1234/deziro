@extends('admin.layouts.app')

@section('content')
<div class="row">
	<div class="col-12 col-lg-10 mx-auto">
		<div class="card shadow-sm">
			<div class="card-header d-flex justify-content-between align-items-center">
				<h5 class="mb-0">Add Product</h5>
				<a href="{{ route('admin.storeProduct.listStoreProduct') }}" class="btn btn-sm btn-outline-secondary">Back</a>
			</div>
			<div class="card-body">
				@if ($errors->any())
				<div class="alert alert-danger">
					<ul class="mb-0">
						@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
				@endif

				<form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
					@csrf
					<div class="row g-3">
						<div class="col-12">
							<label for="user_id" class="form-label">Store</label>
							<select id="user_id" name="user_id" class="form-select" required>
								<option value="" disabled selected>Choose store</option>
								@foreach ($stores as $store)
								<option value="{{ $store->id }}" @selected(old('user_id')==$store->id)>{{ $store->store_name ?? ($store->first_name.' '.$store->last_name) }} (ID: {{ $store->id }})</option>
								@endforeach
							</select>
						</div>

						<div class="col-12">
							<label for="name" class="form-label">Name</label>
							<input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required />
						</div>

						<div class="col-6 col-md-4">
							<label for="type" class="form-label">Type</label>
							<select id="type" name="type" class="form-select" required>
								<option value="sell" @selected(old('type')=='sell')>Sell</option>
								<option value="bid" @selected(old('type')=='bid')>Bid</option>
								<option value="swap" @selected(old('type')=='swap')>Swap</option>
							</select>
						</div>
						<div class="col-6 col-md-4">
							<label for="product_type" class="form-label">Product Type</label>
							<select id="product_type" name="product_type" class="form-select" required>
								<option value="simple" @selected(old('product_type')=='simple')>Simple</option>
								<option value="configurable" @selected(old('product_type')=='configurable')>Configurable</option>
							</select>
						</div>
						<div class="col-12 col-md-4">
							<label for="quantity" class="form-label">Quantity</label>
							<input type="number" id="quantity" name="quantity" class="form-control" value="{{ old('quantity', 1) }}" min="0" />
						</div>

						<div class="col-12 col-md-6">
							<label for="category_id" class="form-label">Category</label>
							<select id="category_id" name="category_id" class="form-select" required>
								<option value="" disabled selected>Choose category</option>
								@foreach ($categories as $category)
								<option value="{{ $category->id }}" @selected(old('category_id')==$category->id)>{{ $category->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-12 col-md-6">
							<label for="brand_id" class="form-label">Brand (optional)</label>
							<select id="brand_id" name="brand_id" class="form-select">
								<option value="">— Select brand —</option>
								@foreach ($brands as $brand)
								<option value="{{ $brand->id }}" @selected(old('brand_id')==$brand->id)>{{ $brand->name }}</option>
								@endforeach
							</select>
							<small class="text-muted">Or enter a new brand name to create it and attach to category.</small>
							<input type="text" name="brand_name" class="form-control mt-2" placeholder="New brand name (optional)" value="{{ old('brand_name') }}" />
						</div>

						<div class="col-12 col-md-4">
							<label for="price" class="form-label">Price</label>
							<input type="number" step="0.01" id="price" name="price" class="form-control" value="{{ old('price') }}" required />
						</div>
						<div class="col-12 col-md-4">
							<label for="special_price" class="form-label">Special Price</label>
							<input type="number" step="0.01" id="special_price" name="special_price" class="form-control" value="{{ old('special_price') }}" />
						</div>
						<div class="col-12 col-md-4">
							<label for="wrap_as_gift_price" class="form-label">Gift Wrap Price</label>
							<input type="number" step="0.01" id="wrap_as_gift_price" name="wrap_as_gift_price" class="form-control" value="{{ old('wrap_as_gift_price') }}" />
						</div>

						<div class="col-12">
							<label for="description" class="form-label">Description</label>
							<textarea id="description" name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
						</div>

						<div class="col-12">
							<label class="form-label">Images</label>
							<input type="file" name="images[]" class="form-control" multiple accept=".bmp,.jpeg,.jpg,.png,.webp" />
						</div>
					</div>

					<!-- Send empty attributes array to satisfy repository expectations -->
					<input type="hidden" name="attributes" value="{{ json_encode([]) }}" />

					<div class="d-grid d-sm-flex gap-2 justify-content-end mt-4">
						<button type="submit" class="btn btn-primary px-4">Create</button>
						<a href="{{ route('admin.storeProduct.listStoreProduct') }}" class="btn btn-light">Cancel</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection


