# 静态分页组件使用说明

## 组件位置
`resources/views/components/pagination-static.blade.php`

## 功能特性
- ✅ 静态 URL 格式：`/page/2` 而不是 `?page=2`
- ✅ 第一页不显示 `/page/1` 后缀
- ✅ 智能页码显示（最多显示 5 个页码）
- ✅ 自动添加省略号 `...`
- ✅ Bootstrap 5 样式
- ✅ 响应式设计

## 使用方法

### 基本用法
```blade
@include('components.pagination-static', ['paginator' => $articles])
```

### 参数说明
| 参数 | 类型 | 必需 | 说明 |
|------|------|------|------|
| `paginator` | LengthAwarePaginator | 是 | Laravel 分页对象 |

## 示例

### 示例 1：文章列表分页
```blade
<!-- 控制器 -->
$articles = Article::paginate(10);

<!-- 视图 -->
@foreach($articles as $article)
  <div>{{ $article->title }}</div>
@endforeach

@include('components.pagination-static', ['paginator' => $articles])
```

### 示例 2：搜索结果分页
```blade
<!-- 控制器 -->
$results = Article::where('title', 'like', "%{$search}%")->paginate(20);

<!-- 视图 -->
@foreach($results as $result)
  <div>{{ $result->title }}</div>
@endforeach

@include('components.pagination-static', ['paginator' => $results])
```

### 示例 3：分类页面分页
```blade
<!-- 控制器 -->
$products = Product::where('category_id', $categoryId)->paginate(15);

<!-- 视图 -->
@foreach($products as $product)
  <div>{{ $product->name }}</div>
@endforeach

@include('components.pagination-static', ['paginator' => $products])
```

## URL 生成规则

组件会根据当前 URL 自动生成正确的分页链接：

| 当前 URL | 第 1 页 | 第 2 页 | 第 3 页 |
|----------|---------|---------|---------|
| `/blog` | `/blog` | `/blog/page/2` | `/blog/page/3` |
| `/blog/page/2` | `/blog` | `/blog/page/2` | `/blog/page/3` |
| `/blog/AI-Checker` | `/blog/AI-Checker` | `/blog/AI-Checker/page/2` | `/blog/AI-Checker/page/3` |
| `/blog/AI-Checker/page/3` | `/blog/AI-Checker` | `/blog/AI-Checker/page/2` | `/blog/AI-Checker/page/3` |

## 页码显示逻辑

- 最多显示 5 个页码按钮
- 当前页居中显示（前后各 2 页）
- 超过范围的页码用 `...` 表示

### 显示示例

**总共 10 页，当前第 1 页：**
```
< 1 2 3 4 5 ... 10 >
```

**总共 10 页，当前第 5 页：**
```
< 1 ... 3 4 5 6 7 ... 10 >
```

**总共 10 页，当前第 10 页：**
```
< 1 ... 6 7 8 9 10 >
```

## 自定义样式

如果需要自定义样式，可以复制组件并修改 CSS 类：

```blade
<!-- 修改分页容器的对齐方式 -->
<ul class="pagination justify-content-center">  <!-- 居中对齐 -->
<ul class="pagination justify-content-start">   <!-- 左对齐 -->
<ul class="pagination justify-content-end">     <!-- 右对齐（默认） -->

<!-- 修改分页大小 -->
<ul class="pagination pagination-lg">  <!-- 大号 -->
<ul class="pagination pagination-sm">  <!-- 小号 -->
```

## 注意事项

1. **路由配置要求**：使用此组件需要配置静态分页路由
   ```php
   Route::get('/blog/page/{page}', [Controller::class, 'index']);
   Route::get('/blog/{category}/page/{page}', [Controller::class, 'index']);
   ```

2. **控制器处理**：需要在控制器中处理 `$page` 参数
   ```php
   public function index(Request $request, $page = null) {
       if ($page) {
           $request->merge(['page' => $page]);
       }
       $articles = Article::paginate(10);
       return view('index', compact('articles'));
   }
   ```

3. **不显示分页**：如果只有一页或没有数据，组件会自动隐藏

## 浏览器兼容性

- Chrome / Edge: ✅
- Firefox: ✅
- Safari: ✅
- IE 11: ❌（不支持 Bootstrap 5）
