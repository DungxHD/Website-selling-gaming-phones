<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/Product.php';

// =========================================================
// NHẬN XÉT TIẾN ĐỘ - FRONTEND CONTROLLER
// =========================================================
// 1) Nhận xét theo sơ đồ chức năng bạn đưa:
//    - Phần "Trang chủ" đã đạt đúng vai trò Controller: gọi Model lấy dữ liệu sản phẩm bán chạy,
//      sản phẩm mới và sản phẩm nổi bật để đẩy sang View. Mục này có thể xem là đã xong.
//    - Phần "Cửa hàng & Tìm kiếm" đã có các bước quan trọng:
//      + hiển thị danh sách sản phẩm,
//      + tìm kiếm thường,
//      + tìm kiếm nâng cao,
//      + sắp xếp và phân trang.
//      => Đây là phần đã làm khá tốt và bám tương đối sát sơ đồ chức năng.
//    - Phần "Chi tiết sản phẩm" đã có lấy sản phẩm theo id và sản phẩm liên quan cùng hãng.
//      => Đã đúng hướng, phù hợp với luồng khi người dùng bấm vào sản phẩm.
//    - Các phần "Giỏ hàng", "Thanh toán", "Tài khoản User" không nằm trong file này,
//      nên có thể xem FrontendController hiện mới hoàn thành nhóm chức năng duyệt sản phẩm.
//
// 2) Điểm làm tốt:
//    - Controller không viết SQL trực tiếp, như vậy là đúng nguyên tắc MVC.
//    - Có dùng constructor để nhận Product model, đây là cách làm tốt và dễ mở rộng.
//    - Cấu trúc trả về ['view' => ..., 'data' => ...] rõ ràng, phù hợp cho người mới học.
//
// 3) Điểm nên cải thiện:
//    - File này đang xử lý khá nhiều việc trong shop(): đọc GET, lọc, phân trang, chọn kiểu tìm kiếm.
//      Với ASM thì chấp nhận được, nhưng về sau nên tách nhỏ để dễ đọc hơn.
//    - Tên biến filter chưa thật sự đồng bộ giữa Controller, Model và View
//      như: search, action, name, company, status, arrange...
//      Khi sửa, nên thống nhất 1 kiểu tên ngay từ đầu.
//    - Một số giá trị còn viết trực tiếp trong code như 8, 12, 20...
//      Về sau nên gom thành biến hoặc hằng số để dễ chỉnh.
//    - Khi không tìm thấy sản phẩm ở detail(), hiện tại đang trả về lại trang shop.
//      Cách này vẫn chạy được, nhưng chuẩn hơn là hiện thông báo hoặc trang 404.
//
// 4) Hướng sửa về sau:
//    - Bước 1: Thống nhất tên filter giữa form View - Controller - Model.
//    - Bước 2: Tách phần đọc và làm sạch dữ liệu $_GET ra hàm riêng.
//    - Bước 3: Bổ sung xử lý 404 hoặc thông báo rõ khi không tìm thấy sản phẩm.
//    - Bước 4: Nếu dự án lớn hơn, có thể tách riêng ShopController để file gọn hơn.

class FrontendController
{

    public function __construct(
        private Product $productModel = new Product()
    ) {}

    public function home(): array
    {
        return [
            'view' => 'frontend/home.php',
            'data' => [
                'bestSellers' => $this->productModel->getBestSellers(8),
                'products'    => $this->productModel->getAll(8),
                'hotProducts' => $this->productModel->getHotproducts(),
                'pagination'  => ['current' => 1, 'totalPages' => 1, 'basePage' => 'shop'],
            ],
        ];
    }

    public function shop(): array
    {
        // NHẬN XÉT:
        // - Đây là phần quan trọng nhất theo sơ đồ "Cửa hàng & Tìm kiếm" và bạn đã làm được khá nhiều:
        //   + xem danh sách sản phẩm,
        //   + tìm kiếm theo từ khóa,
        //   + tìm kiếm nâng cao,
        //   + sắp xếp,
        //   + phân trang.
        // - Cách dùng match(true) là hợp lý, giúp đoạn xử lý ngắn gọn hơn so với nhiều if/else.
        // - Tuy nhiên số lượng biến lọc đang khá nhiều, người mới học sẽ dễ bị rối.
        //   Gợi ý sửa sau: gom toàn bộ bộ lọc vào 1 mảng $filters có cấu trúc cố định ngay từ đầu.

        // Phân trang

        $currentPage = max(1, (int)($_GET['p'] ?? 1)); // Số trang
        $perPage = 12; // Số lượng hiển thị danh sách trong 1 trang

        // Hứng tham số từ URL
        $searchNormal = trim($_GET['search'] ?? '');
        $action       = trim($_GET['action'] ?? '');

        // Hứng bộ lọc nâng cao(Mảng bộ lọc nâng cao)
        $advancedFilters = array_map(
            fn($key) => trim($_GET[$key] ?? ''),
            ['name', 'company', 'status', 'price_min', 'price_max', 'arrange']
        );

        // Gộp tất cả filter để truyền về View (giữ giá trị form sau submit)
        $allFilters = ['search' => $searchNormal, 'action' => $action] + $advancedFilters;

        [$totalProducts, $products] = match (true) {
            // Tìm nâng cao 
            $action === 'advanced_search' => [
                $this->productModel->getAdvancedSearchCount($advancedFilters),
                $this->productModel->getAdvancedSearchProducts($advancedFilters, $currentPage, $perPage),
            ],
            // Tìm thường
            $searchNormal !== '' => [
                $this->productModel->getNormalSearchCount($searchNormal),
                $this->productModel->getNormalSearchProducts($searchNormal, $currentPage, $perPage),
            ],
            // Mặc định: Hiển thị tất cả sản phẩm
            default => [
                $this->productModel->getTotalProductsCount(),
                $this->productModel->getProductsByPage($currentPage, $perPage),
            ],
        };

        // Tính toán phân trang 
        $totalPages = max(1, (int)ceil($totalProducts / $perPage));
        $currentPage = min($currentPage, $totalPages); // Không vượt quá tổng số trang

        // NHẬN XÉT:
        // - Đoạn return bên dưới đã đúng chức năng Controller: chuẩn bị dữ liệu cho View.
        // - Gợi ý sửa sau: nên chú thích rõ từng phần filters, brands, products, pagination
        //   để người đọc hiểu ngay dữ liệu nào phục vụ lọc, dữ liệu nào phục vụ hiển thị.
        return [
            'view' => 'frontend/shop.php',
            'data' => [
                'filters'    => $allFilters,
                'brands'     => $this->productModel->getBrands(),
                'products'   => $products,
                'pagination' => [
                    'current'    => $currentPage,
                    'totalPages' => $totalPages,
                    'basePage'   => 'shop',
                    // Array filter: Lọc bỏ filter rỗng để URL gọn gàng
                    'query'      => array_filter($allFilters, fn($v) => $v !== ''),
                ],
            ],
        ];
    }

    /**
     * Chi tiết sản phẩm
     */
    public function detail(int $id): array
    {
        // NHẬN XÉT:
        // - Cách nhận $id kiểu int là tốt, giúp rõ kiểu dữ liệu đầu vào.
        // - Theo sơ đồ chức năng, khi người dùng bấm vào sản phẩm thì phải hiện chi tiết sản phẩm,
        //   và hàm này đang đáp ứng đúng hướng đó.
        // - Đã có xử lý trường hợp không tìm thấy sản phẩm, đây là điểm tốt.
        // - Tuy nhiên hiện tại đang trả về lại trang shop nên người dùng khó hiểu chuyện gì xảy ra.
        //   Gợi ý sửa sau: nên trả trang 404 hoặc thông báo "Không tìm thấy sản phẩm".
        $product = $this->productModel->getById($id);
        if (!$product) {
            return [
                'view' => 'frontend/shop.php',
                'data' => [
                    'filters'    => [],
                    'brands'     => $this->productModel->getBrands(),
                    'products'   => $this->productModel->getAll(20),
                    'pagination' => ['current' => 1, 'totalPages' => 1, 'basePage' => 'shop', 'query' => []],
                ],
            ];
        }

        // Sản phẩm liên quan cùng thương hiệu
        // NHẬN XÉT:
        // - Việc tận dụng lại hàm search() giúp tránh viết lại query là hợp lý.
        // - Tuy nhiên để đúng nghĩa hơn, về sau nên có hàm riêng như getRelatedProductsByBrand()
        //   để khi đọc vào là hiểu ngay mục đích của đoạn xử lý này.
        $related = $this->productModel->search('', (string)($product['brand'] ?? ''), 8);

        return [
            'view' => 'frontend/detail.php',
            'data' => [
                'product'         => $product,
                'relatedProducts' => $related,
            ],
        ];
    }
}
