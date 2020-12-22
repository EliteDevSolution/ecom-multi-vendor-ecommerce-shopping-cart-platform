<?php
header ("Content-Type:text/css");
$color = "#746EF1"; // Change your Color Here


function checkhexcolor($color) {
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

if( isset( $_GET[ 'color' ] ) AND $_GET[ 'color' ] != '' ) {
    $color = "#".$_GET[ 'color' ];
}

if( !$color OR !checkhexcolor( $color ) ) {
    $color = "#746EF1";
}

?>

.navbar-area.bg-light-blue {
    background-color: <?php echo $color; ?>;
}

.hero-carousel .owl-prev {
    border: 2px solid <?php echo $color; ?>;
    color: <?php echo $color; ?>;
}
.hero-carousel .owl-prev:hover {
    border: 2px solid <?php echo $color; ?>;
    background-color: <?php echo $color; ?>;
    color: #fff;
}
.hero-carousel .owl-next {
    border: 2px solid <?php echo $color; ?>;
    color: <?php echo $color; ?>;
}
.hero-carousel .owl-next:hover {
    border: 2px solid <?php echo $color; ?>;
    background-color: <?php echo $color; ?>;
}
.header-area-three.home-six .header-inner .subtitle {
    color: <?php echo $color; ?>;
}
.header-area-three.home-six .header-inner .btn-wrapper .boxed-btn {
    background-color: <?php echo $color; ?>;
}
.base-bg {
    background-color: <?php echo $color; ?> !important;
}
.home-six-trending-sellter-filter-nav .nav-tabs .nav-item .nav-link.active, .home-six-trending-sellter-filter-nav .nav-tabs .nav-item .nav-link:hover {
    color: <?php echo $color; ?>;
}
a.view-all-products {
    background-color:<?php echo $color; ?>;
    border: 1px solid<?php echo $color; ?>;
}
a.view-all-products:hover {
    color:<?php echo $color; ?>;
}
.recently-added-area .recently-added-nav-menu ul li {
    background-color: <?php echo $color; ?>;
}
ul.home-subcategories {
    background-color: <?php echo $color; ?>;
}
ul.home-subcategories li a:hover {
    color: <?php echo $color; ?>;
}
ul.home-subcategories li a:hover {
    color: <?php echo $color; ?>;
}
ul.home-subcategories li a:hover {
    color: <?php echo $color; ?>;
}
.submit-btn {
    background-color: <?php echo $color; ?>;
}
.back-to-top {
    background-color: <?php echo $color; ?>;;
}
footer .social-links a:hover {
    color: <?php echo $color; ?>;
}
.single-new-collection-item .content .price .sprice {
    color: <?php echo $color; ?>;
}
.home-six-trending-sellter-filter-nav .nav-tabs .nav-item .nav-link {
    background-color: <?php echo $color; ?>;
}
.support-bar-two.home-6 .right-content ul li .single-support-info-item .content .title {
    color: <?php echo $color; ?>;
}
.product-details-content-area .right-content-area .top-content .orders {
    background-color: <?php echo $color; ?>;
}
.product-details-content-area .right-content-area .top-content .orders:after {
    background-color: <?php echo $color; ?>;
}
.product-details-content-area .right-content-area .bottom-content .price-area .left .sprice {
    color: <?php echo $color; ?>;
}
.product-details-content-area .right-content-area .bottom-content .paction .btn-wrapper .boxed-btn {
    background-color: #232b37;
}
.product-details-tab-nav .nav.nav-tabs .nav-item .nav-link.active {
    background-color: <?php echo $color; ?>;
    border-color: <?php echo $color; ?>;
}
.base-txt {
    color: <?php echo $color; ?> !important;
}
.product-details-tab-nav .nav.nav-tabs .nav-item .nav-link:hover {
    background-color: <?php echo $color; ?>;
    border-color: <?php echo $color; ?>;
}
.footer-arae-one .footer-widget.about .widget-body .contact-info-list li .single-contact-info:hover .icon {
    background-color: <?php echo $color; ?>;
}
.product-details-content-area .right-content-area .bottom-content .cat {
    color: <?php echo $color; ?>;
}
.support-bar-two.home-6 .right-content ul li .support-search-area .search-form .form-element .input-field {
    border: 2px solid <?php echo $color; ?>;
}
.support-bar-two.home-6 .right-content ul li .support-search-area .search-form .submit-btn {
    border: 2px solid <?php echo $color; ?>;
    color: <?php echo $color; ?>;
}
.support-bar-two.home-6 .right-content ul li .support-search-area .search-form .submit-btn:hover {
    background-color: <?php echo $color; ?>;
}
.support-bar-two.home-6 .right-content ul li .support-search-area .search-form .form-element.has-icon .the-icon:after {
    color: <?php echo $color; ?>;
}
.support-bar-two.home-6 .right-content ul li .single-support-info-item .icon {
    color: <?php echo $color; ?>;
}
.support-bar-area .right-content-area ul li a:hover {
    color: <?php echo $color; ?>;
}
.product-details-content-area .right-content-area .bottom-content .product-spec li .right.base-color {
    color: <?php echo $color; ?>;
}
.navbar-area.nav-fixed.home-6 {
    background-color: <?php echo $color; ?>;
}
.navbar-area .navbar-collapse .navbar-nav .nav-item:hover .dropdown-menu .dropdown-item:hover {
    background-color: <?php echo $color; ?>;
}
.irs-from, .irs-to, .irs-single {
    background: <?php echo $color; ?> !important;
}
.irs-bar {
    border-top: 1px solid <?php echo $color; ?> !important;
    border-bottom: 1px solid <?php echo $color; ?> !important;
    background: <?php echo $color; ?> !important;
}
.page-item.active .page-link {
    background-color: <?php echo $color; ?>;
    border-color: <?php echo $color; ?>;
}
.page-link {
    color: <?php echo $color; ?>;
}
.category-content-area .right-content-area .top-content .right-content ul li.icon.active, .category-content-area .right-content-area .top-content .right-content ul li.icon:hover {
    background-color: <?php echo $color; ?>;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: <?php echo $color; ?>;
}
.navbar-area .navbar-collapse .navbar-nav .nav-item.mega-menu .mega-menu-wrapper .mega-menu-container .mega-menu-columns .menga-menu-page-links li a:hover {
    color: <?php echo $color; ?>;
}
.category-content-area .category-sidebar .category-filter-widget .cat-list li a:hover {
    color: <?php echo $color; ?>;
}
.contact-info-area .left-content-area ul li .single-contact-info-item .icon {
    background-color: <?php echo $color; ?>;
}
a.sidebar-links.active {
    background-color: <?php echo $color; ?>;
}
a.sidebar-links:hover {
    background-color: <?php echo $color; ?>;
}
.sellers-product-content-area .seller-product-wrapper .sellers-product-inner .bottom-content table thead tr th {
    background-color: <?php echo $color; ?>;
}
h2.order-heading::after {
    background-color: <?php echo $color; ?>;
}
.seller-dashboard-content-area .dashboard-content-wrapper .card-header {
    background-color:<?php echo $color; ?>;
}
.seller-dashboard-table tbody tr td .base-color {
    color:<?php echo $color; ?>;
}
.btn-primary {
    background-color: <?php echo $color; ?>;
    border-color: <?php echo $color; ?>;
}
.sellers-product-content-area .seller-product-wrapper .seller-panel .card-header {
    background-color: <?php echo $color; ?>;
}
.boxed-btn {
    background-color: <?php echo $color; ?>;
}
.recently-added-area .recently-added-carousel .owl-nav div:hover {
    background-color: <?php echo $color; ?>;
}
.product-details-content-area .right-content-area .bottom-content .paction .activities li a:hover {
    background-color: <?php echo $color; ?>;
    border-color: <?php echo $color; ?>;
}
span.discount-badge {
    background-color: <?php echo $color; ?>;
}
span.discount-badge::after {
    border-left: 15px solid <?php echo $color; ?>;
}
.preloader-inner {
  background-color: <?php echo $color; ?>;
}
.sk-fading-circle .sk-circle:before {
  background-color: #fff; }
