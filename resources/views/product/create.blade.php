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
            <form
                action="{{ url('/products') }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <div class="row">
                    <div class="col-lg-9">
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
                        <div
                            class="card"
                            id="images-module"
                        >
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Images') }}</h3>
                            </div>
                            <div class="card-body">
                                <div
                                    id="images"
                                    class="row align-items-end"
                                ></div>
                                <script id="image-input-tmpl" type="text/html">
                                    <div class="col-3">
                                        <img
                                            class="image-preview mb-3"
                                            width="100%"
                                            style="display: none"
                                        />
                                        <input
                                            type="file"
                                            name="images[]"
                                            class="image-input form-control-file"
                                            accept="image/*"
                                        />
                                    </div>
                                </script>
                            </div>
                        </div>
                        <script>
                            var Images = (function() {
                                var $el = $('#images-module');
                                var $images = $el.find('#images');

                                var imageInputTmpl = $el.find('#image-input-tmpl').html();
                                var imageCounter = 0;

                                $('body').on('change', '.image-input', function() {
                                    var file = this.files[0];

                                    var $this = $(this);
                                    var $wrapper = $this.closest('div');
                                    var $preview = $wrapper.find('.image-preview');

                                    $preview.removeAttr('src');
                                    $preview.hide();

                                    if (!file) {
                                        if (imageCounter > 1) {
                                            imageCounter--;
                                            $wrapper.remove();
                                        }

                                        return;
                                    }

                                    var reader = new FileReader();

                                    reader.onload = function(event) {
                                        $preview.attr('src', event.target.result);
                                        $preview.show();
                                        append();
                                    };

                                    reader.readAsDataURL(file);
                                });

                                function append() {
                                    $images.append(
                                        ejs.render(imageInputTmpl)
                                    );

                                    imageCounter++;
                                }

                                function init() {
                                    append();
                                }

                                init();
                            })();
                        </script>
                    </div>
                    <div class="col-lg-3">
                        <div
                            class="card"
                            id="featured-image-module"
                        >
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Featured image') }}</h3>
                            </div>
                            <div class="card-body">
                                <img
                                    class="mb-3"
                                    id="featured-image-preview"
                                    width="100%"
                                    style="display: none"
                                />
                                <input
                                    type="file"
                                    id="featured-image"
                                    name="featured_image"
                                    class="form-control-file"
                                    accept="image/*"
                                />
                            </div>
                        </div>
                        <script>
                            var FeaturedImage = (function() {
                                var $el = $('#featured-image-module');
                                var $preview = $el.find('#featured-image-preview');
                                var $input = $el.find('#featured-image');

                                $input.on('change', function() {
                                    var file = this.files[0];

                                    $preview.removeAttr('src');
                                    $preview.hide();

                                    if (!file) return;

                                    var reader = new FileReader();

                                    reader.onload = function(event) {
                                        $preview.attr('src', event.target.result);
                                        $preview.show();
                                    };

                                    reader.readAsDataURL(file);
                                });
                            })();
                        </script>
                    </div>
                </div>
                <button
                    type="submit"
                    class="btn btn-primary"
                >{{ __('Save') }}</button>
            </form>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</x-app>
