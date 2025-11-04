#!/bin/bash
# Script tự động setup và build theme sau khi pull code từ git

set -e

echo "🚀 Đang setup theme mooms_dev_v1..."

# Kiểm tra xem có thư mục dist không
if [ ! -d "dist" ]; then
    echo "⚠️  Thư mục dist không tồn tại. Cần build assets..."
else
    echo "✓ Thư mục dist đã tồn tại"
fi

# Kiểm tra node_modules
if [ ! -d "node_modules" ]; then
    echo "📦 Đang cài đặt dependencies..."
    if command -v yarn &> /dev/null; then
        yarn install
    else
        npm install
    fi
else
    echo "✓ Dependencies đã được cài đặt"
fi

# Build assets
echo "🔨 Đang build assets..."
if command -v yarn &> /dev/null; then
    yarn build
else
    npm run build
fi

echo "✅ Setup hoàn tất! Theme đã sẵn sàng sử dụng."

