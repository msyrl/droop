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
                        href="{{ url('/sales-orders') }}"
                        class="btn btn-default"
                    >
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
                <div class="col-auto">
                    <h1 class="m-0">{{ $salesOrder->name }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <form
                action="{{ url('/sales-orders/' . $salesOrder->id) }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <dl>
                                    <dt>{{ __('Name') }}</dt>
                                    <dd>{{ $salesOrder->name }}</dd>
                                    <dt>{{ __('Status') }}</dt>
                                    <dd>{{ $salesOrder->formatted_status }}</dd>
                                    <dt>{{ __('Paid') }}</dt>
                                    <dd>{{ $salesOrder->formatted_paid }}</dd>
                                    <dt>{{ __('User') }}</dt>
                                    <dd>{{ $salesOrder->user->email }}</dd>
                                    <dd>{{ $salesOrder->user->name }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</x-app>
