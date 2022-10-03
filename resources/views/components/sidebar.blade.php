<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary">
    <!-- Brand Logo -->
    <a
        href="#"
        class="brand-link"
    >
        <img
            src="https://via.placeholder.com/60"
            class="brand-image img-circle"
            style="opacity: .8"
        >
        <span class="brand-text font-weight-light">{{ Config::get('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul
                class="nav nav-pills nav-sidebar flex-column nav-legacy"
                data-widget="treeview"
                role="menu"
                data-accordion="false"
            >
                <x-nav-item
                    :href="url('/')"
                    activeHref="/"
                >
                    <i class="nav-icon fas fa-home"></i>
                    <p>{{ __('Home') }}</p>
                </x-nav-item>
                <x-nav-item
                    :href="url('/catalogs')"
                    activeHref="/catalogs"
                >
                    <i class="nav-icon fas fa-boxes"></i>
                    <p>{{ __('Catalog') }}</p>
                </x-nav-item>
                <x-nav-item
                    :href="url('/users/cart')"
                    activeHref="/users/cart"
                >
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <p>{{ __('Cart') }}</p>
                </x-nav-item>
                <x-nav-item
                    :href="url('/users/purchase-histories')"
                    activeHref="/users/purchase-histories"
                >
                    <i class="nav-icon fas fa-history"></i>
                    <p>{{ __('Purchase histories') }}</p>
                </x-nav-item>
                @canany([\App\Enums\PermissionEnum::view_roles()->value, \App\Enums\PermissionEnum::view_users()->value,
                    \App\Enums\PermissionEnum::view_sales_orders()->value])
                @endcanany
                <li class="nav-header">{{ __('Admin side') }}</li>
                @can(\App\Enums\PermissionEnum::view_sales_orders()->value)
                    <x-nav-item
                        :href="url('/sales-orders')"
                        activeHref="sales-orders"
                    >
                        <i class="nav-icon fas fa-dollar-sign"></i>
                        <p>{{ __('Sales orders') }}</p>
                    </x-nav-item>
                @endcan
                @can(\App\Enums\PermissionEnum::view_products()->value)
                    <x-nav-item
                        :href="url('/products')"
                        activeHref="products"
                    >
                        <i class="nav-icon fas fa-box"></i>
                        <p>{{ __('Products') }}</p>
                    </x-nav-item>
                @endcan
                @can(\App\Enums\PermissionEnum::view_users()->value)
                    <x-nav-item
                        :href="url('/users')"
                        activeHref="users"
                    >
                        <i class="nav-icon fas fa-users"></i>
                        <p>{{ __('Users') }}</p>
                    </x-nav-item>
                @endcan
                @can(\App\Enums\PermissionEnum::view_roles()->value)
                    <x-nav-item
                        :href="url('/roles')"
                        activeHref="roles"
                    >
                        <i class="nav-icon fas fa-lock"></i>
                        <p>{{ __('Roles') }}</p>
                    </x-nav-item>
                @endcan
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
