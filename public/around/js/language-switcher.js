/**
 * 语言切换功能
 */
document.addEventListener('DOMContentLoaded', function() {
    const languageDropdownItems = document.querySelectorAll('[data-locale]');
    
    languageDropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            const selectedLocale = this.getAttribute('data-locale');
            const currentLangSpan = document.querySelector('.current-lang');
            
            // 更新按钮文本
            if (currentLangSpan) {
                currentLangSpan.textContent = selectedLocale === 'en' ? 'EN' : '中';
            }
            
            // 禁用项目防止重复点击
            this.style.pointerEvents = 'none';
            
            // 跳转到语言切换路由
            window.location.href = `/change/${selectedLocale}`;
        });
    });
    
    // 存储当前语言
    const currentLang = document.documentElement.lang || 'en';
    localStorage.setItem('preferred-language', currentLang);
});

 