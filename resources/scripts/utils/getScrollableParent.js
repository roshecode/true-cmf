const getScrollableParentRecursion = node => {
    let computedStyle = window.getComputedStyle(node);

    return /(auto|scroll)/.test(
        computedStyle.overflow + computedStyle.overflowY + computedStyle.overflowX
    ) ? node : node.parentNode === null ? false : getScrollableParentRecursion(node.parentNode);
};

const getScrollableParent = node => (node instanceof HTMLElement || node instanceof SVGElement)
        ? getScrollableParentRecursion(node) || document.scrollingElement || document.documentElement
        : undefined;

export default getScrollableParent;
