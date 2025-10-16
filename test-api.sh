#!/bin/bash

BASE_URL="http://localhost/api"

echo "=== Testing Blog API (Final Fix) ==="

# 1. Проверка доступности API
echo -e "\n1. Testing API availability..."
HEALTH_RESPONSE=$(curl -s -X GET "$BASE_URL/posts" \
  -H "Accept: application/json")

echo "Health check: $HEALTH_RESPONSE"

# 2. Регистрация нового пользователя
echo -e "\n2. Testing registration..."
REGISTER_RESPONSE=$(curl -s -X POST "$BASE_URL/register" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john'$(date +%s)'@example.com",
    "password": "password123"
  }')

echo "Registration response: $REGISTER_RESPONSE"

# Проверяем успешность регистрации
if echo "$REGISTER_RESPONSE" | grep -q "access_token"; then
    echo "Registration successful!"
    ACCESS_TOKEN=$(echo "$REGISTER_RESPONSE" | grep -o '"access_token":"[^"]*' | cut -d'"' -f4)
    echo "Access token: $ACCESS_TOKEN"
else
    echo "Registration failed!"
    echo "Full response: $REGISTER_RESPONSE"
    exit 1
fi

# 3. Создание поста
echo -e "\n3. Testing post creation..."
CREATE_POST_RESPONSE=$(curl -s -X POST "$BASE_URL/posts" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $ACCESS_TOKEN" \
  -d '{
    "title": "My First Post",
    "text": "This is the content of my first post created via API."
  }')

echo "Create post response: $CREATE_POST_RESPONSE"

# 4. Получение всех постов
echo -e "\n4. Testing get all posts..."
GET_POSTS_RESPONSE=$(curl -s -X GET "$BASE_URL/posts" \
  -H "Accept: application/json")

echo "Get all posts response:"
echo "$GET_POSTS_RESPONSE" | python3 -m json.tool 2>/dev/null || echo "$GET_POSTS_RESPONSE"

# 5. Получение моих постов
echo -e "\n5. Testing get my posts..."
GET_MY_POSTS_RESPONSE=$(curl -s -X GET "$BASE_URL/my-posts" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $ACCESS_TOKEN")

echo "Get my posts response:"
echo "$GET_MY_POSTS_RESPONSE" | python3 -m json.tool 2>/dev/null || echo "$GET_MY_POSTS_RESPONSE"

# 6. Логаут
echo -e "\n6. Testing logout..."
LOGOUT_RESPONSE=$(curl -s -X POST "$BASE_URL/logout" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $ACCESS_TOKEN")

echo "Logout response: $LOGOUT_RESPONSE"

echo -e "\n=== API Testing Complete ==="
