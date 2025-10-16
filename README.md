# Blog API Documentation

## Default Access Credentials

### Admin Panel
- **URL:** http://localhost/admin
- **Email:** `admin@blog.com`
- **Password:** `password`

### Test User
- **Email:** `user@blog.com`
- **Password:** `password`

---

## API Endpoints

### Authentication

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `POST` | `/api/register` | User registration | ❌ |
| `POST` | `/api/login` | User login | ❌ |
| `POST` | `/api/logout` | User logout | ✅ |

### 📝 Posts

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `GET` | `/api/posts` | Get all posts | ❌ |
| `GET` | `/api/my-posts` | Get user's posts | ✅ |
| `POST` | `/api/posts` | Create new post | ✅ |

---

## 💻 Example Usage

### User Registration
```bash
curl -X POST http://localhost/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
  }'
