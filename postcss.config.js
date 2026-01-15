const prefixSelector = require('postcss-prefix-selector');

module.exports = {
  plugins: [
    require('tailwindcss/nesting'),
    require('tailwindcss'),
    require('autoprefixer'),
    prefixSelector({
      prefix: '.main-scope',
      transform(prefix, selector, prefixedSelector) {
        if (selector.startsWith('html') || selector.startsWith('body')) {
          return selector;
        }
        return prefixedSelector;
      }
    }),
  ],
};
