#### Requirements

1. PHP 8.0 or higher
2. Composer 2 or higher
3. MySQL

#### Instalation Steps

1. Open your terminal.
1. Copy `.env` file with `cp .env.example .env`.
1. Set environment variables on `.env` file using your favorite text editor.
1. Install dependencies with `composer install`.
1. Generate `APP_KEY` with `php artisan key:generate`
1. Run migrations with `php artisan migrate`
1. Seed data for initial setup `php artisan db:seed`
1. Run app with `php artisan serve`
1. Open `http://127.0.0.1:8000` on your browser.


#### Database

```dbml
Table roles {
    id varchar [not null, pk] // uuid
    created_at timestamp [not null, default: `now()`]
    updated_at timestamp [not null, default: `now() ON UPDATE now()`]
    name varchar [not null]
    guard_name varchar [not null, default: 'web']
}

Table permissions {
    id varchar [not null, pk] // uuid
    created_at timestamp [not null, default: `now()`]
    updated_at timestamp [not null, default: `now() ON UPDATE now()`]
    name varchar [not null]
    guard_name varchar [not null, default: 'web']
}

Table model_has_roles {
    role_id varchar [not null, ref: > roles.id]
    model_type varchar [not null]
    model_id varchar [not null]

    indexes {
        (role_id, model_type, model_id) [pk]
    }
}

Table model_has_permissions {
    permission_id varchar [not null, ref: > permissions.id]
    model_type varchar [not null]
    model_id varchar [not null]

    indexes {
        (permission_id, model_type, model_id) [pk]
    }
}

Table role_has_permissions {
    role_id varchar [not null, ref: > roles.id]
    permission_id varchar [not null, ref: > permissions.id]

    indexes {
        (role_id, permission_id) [pk]
    }
}

Table users {
    id varchar [not null, pk] // uuid
    created_at timestamp [not null, default: `now()`]
    updated_at timestamp [not null, default: `now() ON UPDATE now()`]
    name varchar [not null]
    email varchar [not null, unique]
    email_verified_at timestamp [null]
    password varchar [not null]
    remember_token varchar [null]
}

Table media {
    id varchar [not null, pk] // uuid
    created_at timestamp [not null, default: `now()`]
    updated_at timestamp [not null, default: `now() ON UPDATE now()`]
    model_type varchar [not null]
    model_id varchar [not null]
    uuid varchar [null]
    collection_name varchar [not null]
    name varchar [not null]
    file_name varchar [not null]
    mime_type varchar [null]
    disk varchar [not null]
    conversion_disk varchar [null]
    size bigint [not null, unsigned]
    manipulations json [not null]
    custom_properties json [not null]
    generated_conversions json [not null]
    responsive_images json [not null]
    order_column int [null, unsigned]
}

Table products {
    id varchar [not null, pk] // uuid
    created_at timestamp [not null, default: `now()`]
    updated_at timestamp [not null, default: `now() ON UPDATE now()`]
    name varchar [not null]
    sku varchar [null]
    price bigint [not null, unsigned]
    description longtext [null]
}

Table sales_orders {
    id varchar [not null, pk] // uuid
    created_at timestamp [not null, default: `now()`]
    updated_at timestamp [not null, default: `now() ON UPDATE now()`]
    user_id varchar [not null, ref: > users.id]
    status varchar [not null]
    paid boolean [not null, default: false]
    name varchar [not null]
    quantity bigint [not null, unsigned, default: 0]
    total_line_items_price bigint [not null, unsigned, default: 0]
    total_additional_charge bigint [not null, unsigned, default: 0]
    total_price bigint [not null, unsigned, default: 0]
}

Table sales_order_line_items {
    id varchar [not null, pk] // uuid
    created_at timestamp [not null, default: `now()`]
    updated_at timestamp [not null, default: `now() ON UPDATE now()`]
    sales_order_id varchar [not null, ref: > sales_orders.id]
    product_id varchar [not null]
    name varchar [not null]
    sku varchar [null]
    price bigint [not null, unsigned]
    quantity bigint [not null, unsigned]
    total_price bigint [not null, unsigned]
}

Table carts {
    id varchar [not null, pk] // uuid
    created_at timestamp [not null, default: `now()`]
    updated_at timestamp [not null, default: `now() ON UPDATE now()`]
    user_id varchar [not null, ref: > users.id]
    quantity bigint [not null, unsigned, default: 0]
    total_price bigint [not null, unsigned, default: 0]
}

Table cart_line_items {
    id varchar [not null, pk] // uuid
    created_at timestamp [not null, default: `now()`]
    updated_at timestamp [not null, default: `now() ON UPDATE now()`]
    cart_id varchar [not null, ref: > carts.id]
    product_id varchar [not null]
    name varchar [not null]
    sku varchar [null]
    price bigint [not null, unsigned]
    quantity bigint [not null, unsigned]
    total_price bigint [not null, unsigned]
}
```
