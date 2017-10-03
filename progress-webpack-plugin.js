import chalk from 'chalk';

let startTime,
    hr = process.hrtime(),
    nodeLoadTime = hr[0] * 1e9 + hr[1] - process.uptime() * 1e9;

function now() {
    hr = process.hrtime();

    return (hr[0] * 1e9 + hr[1] - nodeLoadTime) / 1e9;
}

export default (progress, message) => {
    if (progress === 0) {
        startTime = now();
    }

    process.stdout.clearLine();
    process.stdout.cursorTo(0);

    let progressStr = parseInt(20 * progress);

    process.stdout.write('  '
        + chalk.cyan.bold('build')
        + ' ' + '▓'.repeat(progressStr) + '░'.repeat(20 - progressStr) + ' '
        + chalk.green.bold(`${(progress === 1 ? '' : ' ') + (progress * 100).toFixed(0)}%`) + ' '
        + '[' + (now() - startTime).toFixed(2) + ' seconds] '
        + chalk.gray(message));

    if (progress === 1) {
        process.stdout.write('\n\n');
    }
}
