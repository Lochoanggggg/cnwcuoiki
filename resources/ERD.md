# ERD - CNW Clothing

Sơ đồ thực thể - liên kết của các bảng nghiệp vụ hiện có trong hệ thống:

```mermaid
erDiagram
    USERS ||--o{ ORDERS : "đặt"
    CATEGORIES ||--o{ PRODUCTS : "phân loại"
    PRODUCTS ||--o{ PRODUCT_SIZES : "có size"
    PRODUCTS ||--o{ PRODUCT_IMAGES : "có ảnh"
    ORDERS ||--|{ ORDER_ITEMS : "bao gồm"
    PRODUCTS o|--o{ ORDER_ITEMS : "được mua"

    USERS {
        bigint id PK
        string name
        string email UK
        enum role "customer|admin"
        string phone "nullable"
        string address "nullable"
        timestamp email_verified_at "nullable"
        string password
        timestamp created_at
        timestamp updated_at
    }

    CATEGORIES {
        bigint id PK
        string name
        string slug UK
        text description "nullable"
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    PRODUCTS {
        bigint id PK
        bigint category_id FK
        string name
        string slug UK
        text description "nullable"
        decimal price
        unsigned_int stock
        string image "nullable"
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    PRODUCT_SIZES {
        bigint id PK
        bigint product_id FK
        string name
        unsigned_int stock
        timestamp created_at
        timestamp updated_at
    }

    PRODUCT_IMAGES {
        bigint id PK
        bigint product_id FK
        string url
        timestamp created_at
        timestamp updated_at
    }

    ORDERS {
        bigint id PK
        bigint user_id FK
        string order_code UK
        enum status "pending|confirmed|shipping|completed|cancelled"
        decimal total_amount
        string shipping_name
        string shipping_phone
        string shipping_address
        text note "nullable"
        timestamp created_at
        timestamp updated_at
    }

    ORDER_ITEMS {
        bigint id PK
        bigint order_id FK
        bigint product_id FK "nullable"
        string product_name
        unsigned_int quantity
        decimal unit_price
        decimal subtotal
        timestamp created_at
        timestamp updated_at
    }
```

## Diễn giải quan hệ

- Một **user** có thể đặt nhiều **order**; mỗi order thuộc về đúng một user.
- Một **category** có thể chứa nhiều **product**; mỗi product thuộc về đúng một category.
- Một **product** có thể có nhiều **size** và nhiều **ảnh**. Ảnh được lưu bằng URL
  bên ngoài, không upload file lên máy chủ.
- Một **order** phải có ít nhất một **order item**.
- Một **order item** tham chiếu đến một product, nhưng `product_id` có thể rỗng nếu sản phẩm đã bị xóa.
- `product_name`, `unit_price` và `subtotal` trong `order_items` lưu lại dữ liệu tại thời điểm đặt hàng, giúp bảo toàn lịch sử đơn hàng.
- Xóa category bị giới hạn nếu còn product (`restrictOnDelete`).
- Xóa order sẽ xóa các order item liên quan (`cascadeOnDelete`).
- Xóa product sẽ giữ order item nhưng đặt `product_id` thành rỗng (`nullOnDelete`).

## Bảng Laravel kỹ thuật

Các bảng `sessions`, `password_reset_tokens`, `cache`, `cache_locks`, `jobs`,
`job_batches` và `failed_jobs` phục vụ framework, không nằm trong ERD nghiệp vụ
chính.
