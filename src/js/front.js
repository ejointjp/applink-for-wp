window.addEventListener('load', () => {
  // save時に React SVGのプロパティ名のハイフンがなくなる問題を修正
  const buttons = document.querySelectorAll('.wpalb-btn svg')

  buttons.forEach(item => {
    const replacedHtml = item.outerHTML
      .replaceAll('cliprule', 'clip-rule')
      .replaceAll('fillrule', 'fill-rule')
      .replaceAll('strokelinecap', 'stroke-linecap')
      .replaceAll('strokelinejoin', 'stroke-linejoin')
      .replaceAll('strokewidth', 'stroke-width')
    item.outerHTML = replacedHtml
  })
})
