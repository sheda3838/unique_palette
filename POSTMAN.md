# Unique Palette API - Postman Documentation

This document describes the API workflow and endpoints for the Unique Palette project.

## Authentication Workflow

### 1. Register

**Endpoint:** `POST /api/register`
**Body (JSON):**

```json
{
    "name": "Jane Doe",
    "email": "jane@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "buyer"
}
```

**Response:**

```json
{
    "message": "User registered successfully",
    "access_token": "1|abc...",
    "token_type": "Bearer",
    "user": { ... }
}
```

### 2. Login

**Endpoint:** `POST /api/login`
**Body (JSON):**

```json
{
    "email": "jane@example.com",
    "password": "password123"
}
```

**Response:**

```json
{
    "message": "Login successful",
    "access_token": "2|xyz...",
    "token_type": "Bearer",
    "user": { ... }
}
```

### 3. Using the Token

For all protected routes, include the token in the headers:
`Authorization: Bearer <your_token>`

---

## API Endpoints

### Authentication

-   `POST /api/logout` (Protected)
-   `GET /api/verify-email` (Protected)

### User Profile

-   `GET /api/profile` - View profile with address and bank details.
-   `PUT /api/profile` - Update name/email.
-   `POST /api/profile/photo` - Update profile picture (Multipart/Form-Data: `photo`).

### Artworks

-   `GET /api/artworks` - List approved artworks.
-   `GET /api/artworks/{id}` - View artwork details.
-   `POST /api/artworks` (Artist only) - Create artwork (Multipart/Form-Data: `title`, `description`, `price`, `image`).
-   `PUT /api/artworks/{id}` (Artist only) - Update artwork details.
-   `DELETE /api/artworks/{id}` (Artist only) - Delete artwork.

### Cart (Buyer only)

-   `GET /api/cart` - View current items in cart.
-   `POST /api/cart` - Add artwork to cart (`artwork_id`, `quantity`).
-   `DELETE /api/cart/{artwork_id}` - Remove artwork from cart.

### Orders (Buyer only)

-   `GET /api/orders` - View order history.
-   `POST /api/orders` - Place order from current cart.
-   `GET /api/orders/{id}` - View specific order details.

### Admin

-   `GET /api/admin/users` - List all users.
-   `GET /api/admin/orders` - View all orders.
-   `POST /api/admin/artworks/{id}/approve` - Approve a pending artwork.
-   `POST /api/admin/artworks/{id}/reject` - Reject a pending artwork.

---

## Example JSON Responses

### List Artworks

```json
{
    "artworks": [
        {
            "id": 1,
            "title": "Sunset",
            "price": 5000,
            "status": "approved",
            "image_url": "http://localhost:8000/storage/artworks/abc.jpg",
            "user": { "name": "Artist Name" }
        }
    ]
}
```

### View Cart

```json
{
    "cart_items": [
        {
            "id": 1,
            "artwork_id": 5,
            "quantity": 1,
            "artwork": { "title": "Blue Ocean", "price": 12000 }
        }
    ],
    "total": 12000
}
```
