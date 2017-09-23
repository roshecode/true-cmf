export let getWidth = el => el.offsetWidth;

export let getHeight = el => el.offsetHeight;

export let getAbsoluteWidth = el => {
    const styles = window.getComputedStyle(el),
        margin = parseFloat(styles['marginLeft']) + parseFloat(styles['marginRight']);

    return Math.ceil(el.offsetWidth + margin);
};

export let getAbsoluteHeight = el => {
    const styles = window.getComputedStyle(el),
        margin = parseFloat(styles['marginTop']) + parseFloat(styles['marginBottom']);

    return Math.ceil(el.offsetHeight + margin);
};
