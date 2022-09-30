<x-app>
    @if ($errors->any())
        <script>
            toastr.error('{{ $errors->first() }}');
        </script>
    @endif
    @if (Session::has('success'))
        <script>
            toastr.success('{{ Session::get('success') }}');
        </script>
    @endif

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-auto">
                    <a
                        href="{{ url('/products') }}"
                        class="btn btn-default"
                    >
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
                <div class="col-auto">
                    <h1 class="m-0">{{ __('New product') }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form
                        action="{{ url('/products') }}"
                        method="POST"
                        novalidate
                    >
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">
                                        <span>{{ __('Name') }}</span>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ Request::old('name') }}"
                                    />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="sku">
                                        <span>{{ __('SKU') }}</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="sku"
                                        id="sku"
                                        class="form-control @error('sku') is-invalid @enderror"
                                        value="{{ Request::old('sku') }}"
                                    />
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="price">
                                        <span>{{ __('Price') }}</span>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        name="price"
                                        id="price"
                                        class="form-control @error('price') is-invalid @enderror"
                                        value="{{ Request::old('price') }}"
                                    />
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description">
                                        <span>{{ __('Description') }}</span>
                                    </label>
                                    <textarea
                                        name="description"
                                        id="description"
                                        class="form-control @error('description') is-invalid @enderror"
                                        rows="4"
                                    >{{ Request::old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button
                            type="submit"
                            class="btn btn-primary"
                        >{{ __('Save') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</x-app>
