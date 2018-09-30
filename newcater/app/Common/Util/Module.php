<?php

namespace Common\Util;

class Module
{
    public static function Permission()
    {
        $permission["login_system"] = ["name" => "Đăng nhập hệ thống"];
        $permission["role_group"] = [
            "name" => "Nhóm quyền",
            "child" => [
                ["name" => "Xem danh sách", "key" => "view"],
                ["name" => "Thêm mới", "key" => "add"],
                ["name" => "Sửa", "key" => "update"],
                [
                    "name" => "Xóa",
                    "key" => "delete"
                ],
                [
                    "name" => "Chi tiết",
                    "key" => "detail"
                ]
            ]
        ];
        $permission["user"] = [
            "name" => "Người dùng",
            "child" => [
                [
                    "name" => "Xem danh sách",
                    "key" => "view"
                ],
                [
                    "name" => "Thêm mới",
                    "key" => "add"
                ],
                [
                    "name" => "Sửa",
                    "key" => "update"
                ],
                [
                    "name" => "Xóa",
                    "key" => "delete"
                ],
                [
                    "name" => "Quyền",
                    "key" => "rolegroup"
                ]
            ]
        ];
        $permission["category"] = [
            "name" => "Danh mục",
            "child" => [
                [
                    "name" => "Xem danh sách",
                    "key" => "view"
                ],
                [
                    "name" => "Thêm mới",
                    "key" => "add"
                ],
                [
                    "name" => "Sửa",
                    "key" => "update"
                ],
                [
                    "name" => "Xóa",
                    "key" => "delete"
                ],
                [
                    "name" => "Chi tiết",
                    "key" => "detail"
                ]
            ]
        ];
        $permission["article"] = [
            "name" => "Bài viết",
            "child" => [
                [
                    "name" => "Xem danh sách",
                    "key" => "view"
                ],
                [
                    "name" => "Thêm mới",
                    "key" => "add"
                ],
                [
                    "name" => "Sửa",
                    "key" => "update"
                ],
                [
                    "name" => "Xóa",
                    "key" => "delete"
                ],
                [
                    "name" => "Chi tiết",
                    "key" => "detail"
                ]
            ]
        ];
        $permission["category_view"] = [
            "name" => "Hiển thị danh mục",
            "child" => [
                [
                    "name" => "Xem danh sách",
                    "key" => "view"
                ],
                [
                    "name" => "Thêm mới",
                    "key" => "add"
                ],
                [
                    "name" => "Sửa",
                    "key" => "update"
                ],
                [
                    "name" => "Xóa",
                    "key" => "delete"
                ],
                [
                    "name" => "Chi tiết",
                    "key" => "detail"
                ]
            ]
        ];
        $permission["video"] = [
            "name" => "Hiển thị video",
            "child" => [
                [
                    "name" => "Xem danh sách",
                    "key" => "view"
                ]
            ]
        ];

        return $permission;
    }

    public static function Sidebar()
    {
        $sidebar = [
            [
                "name" => "Trang chủ",
                "icon" => "icon-home-outline",
                "key" => "login_system",
                "controller" => "/admin",
            ],
            [
                "name" => "Người dùng",
                "icon" => "icon-user-outline",
                "key" => "user",
                "controller" => "/admin/user",
                'permission' => 'user/*'
            ],

            [
                "name" => "Phim",
                "icon" => "icon-ondemand_video",
                "key" => "film",
                "controller" => "/admin/film",
                'permission' => 'film/*'
            ],

            [
                "name" => "Bài viết",
                "icon" => "icon-newspaper",
                "key" => "article",
                "controller" => "/admin/article",
                'permission' => 'article/*'
            ],

            [
                "name" => "Danh mục",
                "icon" => "icon-folder2",
                "key" => "category",
                "controller" => "/admin/category",
                'permission' => 'category/*'
            ],

            [
                "name" => "Diễn viên",
                "icon" => "icon-person_outline",
                "key" => "character",
                "controller" => "/admin/character",
                'permission' => 'character/*'
            ],

            [
                "name" => "Menu",
                "icon" => "icon-th-list-outline",
                "key" => "menu",
                "controller" => "/admin/menu",
                'permission' => 'menu/*',
            ],

            [
                "name" => "Kênh truyền hình",
                "icon" => "icon-tv",
                "key" => "tv",
                "controller" => "/admin/tv",
                'permission' => 'tv/*'
            ],

            [
                "name" => "Cấu hình hiển thị",
                "icon" => "icon-cogs",
                "key" => "role_group,user",
                "controller" => "javascript:",
                'permission' => 'film/*',
                "child" => [
                    [
                        "name" => "Phim lẻ trong ngày",
                        "icon" => 'fa fa-user',
                        "key" => "feature_film",
                        "controller" => "/admin/feature_film/"
                    ],
                    [
                        "name" => "Quảng cáo Web",
                        "icon" => 'fa fa-user',
                        "key" => "feature_film",
                        "controller" => "/admin/ads/"
                    ],
                    [
                        "name" => "Quảng cáo Player",
                        "icon" => 'fa fa-user',
                        "key" => "feature_film",
                        "controller" => "/admin/ads/video"
                    ],
                    [
                        "name" => "Phim hot",
                        "icon" => 'fa fa-user',
                        "key" => "feature_film",
                        "controller" => "/admin/feature_film/hot"
                    ],
                    [
                        "name" => "Danh mục trang chủ",
                        "icon" => 'fa fa-user',
                        "key" => "home_category",
                        "controller" => "/admin/home_category/"
                    ]
                ]
            ],

            [
                "name" => "Hệ thống",
                "icon" => "icon-cogs",
                "key" => "role_group,user",
                "controller" => "javascript:void(0)",
                'permission' => 'role/*',
                "child" => [
                    [
                        "name" => "Cấu hình chung",
                        "icon" => 'fa fa-user',
                        "key" => "registry",
                        "controller" => "/admin/registry/"
                    ],
                    [
                        "name" => "Nhóm quyền",
                        "icon" => 'fa fa-user',
                        "key" => "registry",
                        "controller" => "/admin/role/"
                    ],
                    [
                        "name" => "Tạo gói dịch vụ",
                        "icon" => 'fa fa-cogs',
                        "key" => "registry",
                        "controller" => "/admin/popcorn/"
                    ],
                    [
                        "name" => "Đơn hàng",
                        "icon" => 'fa fa-cogs',
                        "key" => "registry",
                        "controller" => "/admin/payment/order"
                    ],
                    [
                        "name" => "Giao dịch",
                        "icon" => 'fa fa-cogs',
                        "key" => "registry",
                        "controller" => "/admin/payment/transaction"
                    ],
                    [
                        "name" => "About Us",
                        "icon" => 'fa fa-user',
                        "key" => "registry",
                        "controller" => "/admin/registry/about_us"
                    ],
                    [
                        "name" => "Hướng dẫn sử dụng",
                        "icon" => 'fa fa-user',
                        "key" => "registry",
                        "controller" => "/admin/guide"
                    ],
                    [
                        "name" => "Câu hỏi thường gặp",
                        "icon" => 'fa fa-user',
                        "key" => "registry",
                        "controller" => "/admin/registry/question"
                    ],
                    [
                        "name" => "Đơn hàng",
                        "icon" => 'fa fa-user',
                        "key" => "registry",
                        "controller" => "/admin/payment/order"
                    ], [
                        "name" => "Giao dịch",
                        "icon" => 'fa fa-user',
                        "key" => "registry",
                        "controller" => "admin/payment/transaction "
                    ],
                    [
                        "name" => "Báo lỗi",
                        "icon" => 'fa fa-cogs',
                        "key" => "registry",
                        "controller" => "/admin/film_error/"
                    ],
                ]
            ],
        ];

        return $sidebar;
    }

    public static function is_accept_permission($key)
    {
        if (count($_SESSION['permission']) <= 0) $_SESSION['permission'] = [];
        $tmp = explode(",", $key);
        if (count($tmp) > 0) {
            $rs = array_intersect($tmp, $_SESSION['permission']);
            if (count($rs) > 0) return 1;
            else return 0;
        } else {
            if (in_array($key, $_SESSION['permission'])) return 1;
            else return 0;
        }
    }

    public static function getMenu()
    {
        $menu[] = ['icon' => 'mdi mdi-airplay', 'name' => 'Dashboard', 'link' => "/admin/dashboard/index", 'tag' => '', 'active_key' => 'dashboard', "child" => [], 'role' => [1]];
        $menu[] = ['icon' => 'ion-game-controller-b', 'name' => 'Category', 'link' => "/admin/category", 'tag' => '', 'active_key' => 'category/index', "child" => [], 'role' => [1]];
        $menu[] = ['icon' => 'ion-game-controller-b', 'name' => 'Funding Project', 'link' => "/admin/project", 'tag' => '', 'active_key' => 'project/index', "child" => [], 'role' => [1]];
        $menu[] = ['icon' => 'ion-game-controller-b', 'name' => 'Auction Project', 'link' => "/admin/project/auction", 'tag' => '', 'active_key' => 'project/auction', "child" => [], 'role' => [1]];
        $menu[] = ['icon' => 'ion-game-controller-b', 'name' => 'User', 'link' => "/admin/user", 'tag' => '', 'active_key' => 'user/index', "child" => [], 'role' => [1]];
        $menu[] = ['icon' => 'ion-game-controller-b', 'name' => 'Withdraw', 'link' => "/admin/withdraw", 'tag' => '', 'active_key' => 'withdraw/index', "child" => [], 'role' => [1]];

        /*$menu[] = ['icon' => 'ti-stats-up', 'name' => 'Cấu hình', 'link' => "javascript:", 'tag' => '', 'active_key' => 'report', "child" => [
            ['icon' => '', 'name' => 'Cấu hình chung', 'link' => "/admin/registry", 'active_key' => 'registry/index', 'role' => [1]],
            ['icon' => '', 'name' => 'Danh mục trang chủ', 'link' => "/admin/home_category", 'active_key' => 'home_category/index', 'role' => [1]],
            ['icon' => '', 'name' => 'Slide', 'link' => "/admin/registry/slide", 'active_key' => 'registry/slide', 'role' => [1]],
            ['icon' => '', 'name' => 'Thông tin vận chuyển', 'link' => "/admin/registry/ship", 'active_key' => 'registry/ship', 'role' => [1]],
            ['icon' => '', 'name' => 'Đối tác', 'link' => "/admin/registry/partner", 'active_key' => 'registry/partner', 'role' => [1]],
            ['icon' => '', 'name' => 'Nguười hỗ trợ', 'link' => "/admin/registry/team_support", 'active_key' => 'registry/team_support', 'role' => [1]],
        ], 'role' => [1]];*/

        return $menu;
    }
}