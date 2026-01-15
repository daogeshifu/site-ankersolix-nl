<script src="/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
<!-- feather icon js-->
<script src="/assets/js/icons/feather-icon/feather.min.js"></script>
<script src="/assets/js/icons/feather-icon/feather-icon.js"></script>
<!-- scrollbar js-->
<script src="/assets/js/scrollbar/simplebar.js"></script>
<script src="/assets/js/scrollbar/custom.js"></script>

<!-- Plugins JS start-->
<script src="/assets/js/chart/apex-chart/apex-chart.js"></script>
<script src="/assets/js/chart/apex-chart/stock-prices.js"></script>
<script id="menu" src="/assets/js/sidebar-menu.js"></script>
<script src="/assets/js/slick/slick.min.js"></script>
<script src="/assets/js/slick/slick.js"></script>
<script src="/assets/js/header-slick.js"></script>

<!-- Theme js-->
<script src="/assets/js/script.js"></script>
{{-- Theme customizer disabled in production to avoid 404 errors --}}
{{-- <script src="/assets/js/theme-customizer/customizer.js"></script> --}}
<script src="/assets/js/config.js"></script>

<script>

    // 针对所有外链自动添加nofollow标签
    document.addEventListener("DOMContentLoaded", function() {
        var links = document.querySelectorAll("a[href^='http']");
        links.forEach(function(link) {
            if (link.hostname !== location.hostname) {
                link.setAttribute("rel", "nofollow");
            }
        });
    });

</script>