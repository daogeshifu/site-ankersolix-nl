{{--
  静态分页组件

  使用方法:
  @include('components.pagination-static', ['paginator' => $articles])

  参数:
  - paginator: Laravel 分页对象 (必需)
--}}

@if ($paginator->hasPages())
  @php
    // 基础路径（移除 /page/N）
    $basePath = request()->url();
    // 移除当前 URL 中的 /page/数字 部分
    $basePath = preg_replace('/\/page\/\d+$/', '', $basePath);

    // 生成分页 URL 的辅助函数
    $getPageUrl = function($page) use ($basePath) {
      if ($page == 1) {
        // 第一页返回基础路径
        return $basePath;
      } else {
        // 其他页面添加 /page/N
        return $basePath . '/page/' . $page;
      }
    };

    // 计算显示的页码范围
    $start = max($paginator->currentPage() - 2, 1);
    $end = min($start + 4, $paginator->lastPage());
    $start = max($end - 4, 1);
  @endphp

  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-end">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
        <li class="page-item disabled">
          <span class="page-link" aria-label="Previous">
            <i class="bi-chevron-double-left small"></i>
          </span>
        </li>
      @else
        <li class="page-item">
          <a class="page-link" href="{{ $getPageUrl($paginator->currentPage() - 1) }}" aria-label="Previous">
            <i class="bi-chevron-double-left small"></i>
          </a>
        </li>
      @endif

      {{-- Pagination Elements --}}
      @if($start > 1)
        <li class="page-item">
          <a class="page-link" href="{{ $getPageUrl(1) }}">1</a>
        </li>
        @if($start > 2)
          <li class="page-item disabled"><span class="page-link">...</span></li>
        @endif
      @endif

      @for ($i = $start; $i <= $end; $i++)
        @if ($i == $paginator->currentPage())
          <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
        @else
          <li class="page-item">
            <a class="page-link" href="{{ $getPageUrl($i) }}">{{ $i }}</a>
          </li>
        @endif
      @endfor

      @if($end < $paginator->lastPage())
        @if($end < $paginator->lastPage() - 1)
          <li class="page-item disabled"><span class="page-link">...</span></li>
        @endif
        <li class="page-item">
          <a class="page-link" href="{{ $getPageUrl($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
        </li>
      @endif

      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
        <li class="page-item">
          <a class="page-link" href="{{ $getPageUrl($paginator->currentPage() + 1) }}" aria-label="Next">
            <i class="bi-chevron-double-right small"></i>
          </a>
        </li>
      @else
        <li class="page-item disabled">
          <span class="page-link" aria-label="Next">
            <i class="bi-chevron-double-right small"></i>
          </span>
        </li>
      @endif
    </ul>
  </nav>
@endif
