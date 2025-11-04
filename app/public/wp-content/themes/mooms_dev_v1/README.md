# mooms_dev_v1 Theme

Theme WordPress hiện đại, tập trung hiệu năng, bảo mật và trải nghiệm quản trị.

## Tính năng chính
- Auth chuẩn JSON (login/register/reset) + Google OAuth trên `/wp-login.php`
- MMS Admin & Tools: Security Headers, Resource Hints, Database Cleanup
- Caching & DB: Transient caching, query optimization, auto cleanup
- Pipeline build (đề xuất): critical CSS, service worker, hashed assets

## Hiệu năng
- Loại bỏ bloat (emoji, migrate, assets thừa), defer/async scripts
- Resource Hints: preconnect/dns-prefetch/prefetch theo ngữ cảnh
- Image optimization (WebP/quality/resize theo option)
- Hướng tới LCP/TTI/CLS tốt và điểm PSI 90+

## SEO
- HTML5 semantic, breadcrumbs, lazyload images
- Preload fonts/critical assets, meta cơ bản
- Tối ưu tốc độ tải trang → cải thiện crawl & Core Web Vitals

## Setup và Build

### Lưu ý quan trọng về Git

**Vấn đề:** Thư mục `dist/` (chứa các file build) bị ignore trong `.gitignore`, do đó không được commit vào git. Khi pull code từ git, theme sẽ không hoạt động vì thiếu các file assets.

**Giải pháp:** Sau khi pull code từ git, bạn **PHẢI** chạy build để tạo lại thư mục `dist/`:

```bash
# Cài đặt dependencies (chỉ cần chạy lần đầu)
npm install
# hoặc
yarn install

# Build assets cho production
npm run build
# hoặc
yarn build

# Build assets cho development (với hot reload)
npm run dev
# hoặc
yarn dev
```

### Quy trình làm việc với Git

1. **Khi pull code mới:**
   ```bash
   git pull origin master
   npm install  # Nếu có dependencies mới
   npm run build  # QUAN TRỌNG: Build lại assets
   ```

2. **Khi deploy lên server:**
   - Đảm bảo đã chạy `npm run build` trước khi deploy
   - Hoặc setup CI/CD để tự động build sau khi pull code

3. **Khi develop:**
   ```bash
   npm run dev  # Sử dụng development mode với hot reload
   ```

## Tài liệu
- Tổng hợp: `documents/DOC.md`
- Auth: `documents/AUTH_GUIDE.md`
- Admin & Tools: `documents/ADMIN_GUIDE.md`
- Caching & Queries: `documents/CACHING_GUIDE.md`
- Build: `documents/BUILD_GUIDE.md`
