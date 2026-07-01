@php
    $selectedProductIds = old('related_product_ids', $category?->related_product_ids ?? []);
    $selectedFaqIds = old('related_faq_ids', $category?->related_faq_ids ?? []);
    $quickAnswer = $category?->quick_answer ?? [];
    $quickAnswerItemsText = old('quick_answer_items_text', isset($quickAnswer['items']) && is_array($quickAnswer['items']) ? implode("\n", $quickAnswer['items']) : '');
    $pageDataJson = old('page_data_json', isset($category?->page_data) && is_array($category?->page_data) ? json_encode($category->page_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) : '');
@endphp

<div class="form-section">
    <h5><i class="fa fa-info-circle me-2"></i>基本信息</h5>

    <div class="mb-3">
        <label class="form-label">分类名称<span class="required-star">*</span></label>
        <input type="text"
               class="form-control @error('name') is-invalid @enderror"
               name="name"
               value="{{ old('name', $category->name ?? '') }}"
               placeholder="请输入分类名称，例如 aankoopgids"
               required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">父分类</label>
        <select class="form-select @error('parent_id') is-invalid @enderror" name="parent_id">
            <option value="">-- 无父分类（顶级分类）--</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ (string) old('parent_id', $category->parent_id ?? '') === (string) $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        @error('parent_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <input type="hidden" name="is_active" value="0">
        <div class="form-check form-switch">
            <input class="form-check-input @error('is_active') is-invalid @enderror"
                   type="checkbox"
                   name="is_active"
                   value="1"
                   id="isActive"
                   @checked(old('is_active', $category->is_active ?? true))>
            <label class="form-check-label" for="isActive">前台展示</label>
        </div>
        <div class="form-text">关闭后，该分类及其分类页不会在前台展示。</div>
        @error('is_active')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-0">
        <label class="form-label">描述</label>
        <textarea class="form-control @error('description') is-invalid @enderror"
                  name="description"
                  rows="3"
                  placeholder="请输入分类描述">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-section">
    <h5><i class="fa fa-link me-2"></i>专题关联数据</h5>

    <div class="mb-3">
        <label class="form-label">关联产品</label>
        <select class="form-select select2 @error('related_product_ids') is-invalid @enderror" name="related_product_ids[]" multiple data-placeholder="请选择要在专题页展示的产品">
            @foreach($products as $product)
                <option value="{{ $product->id }}" {{ in_array($product->id, array_map('intval', $selectedProductIds ?? []), true) ? 'selected' : '' }}>
                    {{ $product->title }}@if($product->brand) · {{ $product->brand }}@endif
                </option>
            @endforeach
        </select>
        <div class="form-text">不选时，前台自动显示模板中的默认产品卡片。</div>
        @error('related_product_ids')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        @error('related_product_ids.*')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-0">
        <label class="form-label">关联 FAQ</label>
        <select class="form-select select2 @error('related_faq_ids') is-invalid @enderror" name="related_faq_ids[]" multiple data-placeholder="请选择专题页底部 FAQ">
            @foreach($faqs as $faq)
                <option value="{{ $faq->id }}" {{ in_array($faq->id, array_map('intval', $selectedFaqIds ?? []), true) ? 'selected' : '' }}>
                    [{{ strtoupper($faq->locale ?? 'NL') }}] {{ $faq->product?->title ?? '未关联产品' }} · {{ $faq->question }}
                </option>
            @endforeach
        </select>
        <div class="form-text">不选时，前台自动显示模板默认 FAQ。</div>
        @error('related_faq_ids')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        @error('related_faq_ids.*')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-section">
    <h5><i class="fa fa-bolt me-2"></i>快速回答</h5>

    <div class="mb-3">
        <label class="form-label">快速回答标题</label>
        <input type="text"
               class="form-control @error('quick_answer_title') is-invalid @enderror"
               name="quick_answer_title"
               value="{{ old('quick_answer_title', $quickAnswer['title'] ?? '') }}"
               placeholder="留空则使用模板默认标题">
        @error('quick_answer_title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">快速回答摘要</label>
        <textarea class="form-control @error('quick_answer_summary') is-invalid @enderror"
                  name="quick_answer_summary"
                  rows="4"
                  placeholder="留空则使用模板默认摘要">{{ old('quick_answer_summary', $quickAnswer['summary'] ?? '') }}</textarea>
        <div class="form-text">对应前台右侧的 “Korte antwoord” 模块主文案。</div>
        @error('quick_answer_summary')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-0">
        <label class="form-label">快速回答要点</label>
        <textarea class="form-control @error('quick_answer_items_text') is-invalid @enderror"
                  name="quick_answer_items_text"
                  rows="4"
                  placeholder="每行一条，不填则前台使用模板默认 3 条">{{ $quickAnswerItemsText }}</textarea>
        @error('quick_answer_items_text')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-section">
    <h5><i class="fa fa-search me-2"></i>SEO / TDK 设置</h5>

    <div class="mb-3">
        <label class="form-label">SEO 标题</label>
        <input type="text"
               class="form-control @error('seo_title') is-invalid @enderror"
               name="seo_title"
               value="{{ old('seo_title', $category->seo_title ?? '') }}"
               placeholder="留空则使用分类名称">
        @error('seo_title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">SEO 描述</label>
        <textarea class="form-control @error('seo_description') is-invalid @enderror"
                  name="seo_description"
                  rows="3"
                  placeholder="搜索引擎结果页显示的描述（建议 150-160 字符）">{{ old('seo_description', $category->seo_description ?? '') }}</textarea>
        @error('seo_description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-0">
        <label class="form-label">SEO 关键词</label>
        <input type="text"
               class="form-control @error('seo_keywords') is-invalid @enderror"
               name="seo_keywords"
               value="{{ old('seo_keywords', $category->seo_keywords ?? '') }}"
               placeholder="多个关键词用逗号分隔">
        @error('seo_keywords')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-section">
    <h5><i class="fa fa-code me-2"></i>页面元素数据（高级 JSON）</h5>

    <div class="mb-0">
        <label class="form-label">页面元素 JSON</label>
        <textarea class="form-control font-monospace @error('page_data_json') is-invalid @enderror"
                  name="page_data_json"
                  rows="16"
                  placeholder="可覆盖 hero、capacity、criteria、steps、costs、cta 等模块；留空则整页走模板默认值。">{{ $pageDataJson }}</textarea>
        <div class="form-text">
            这是可选的高级配置。未填写的字段前台会自动回退到模板 HTML 默认内容，确保页面元素不缺失。
        </div>
        @error('page_data_json')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
