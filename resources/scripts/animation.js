class Animation {
    el = document.body;
    requestId;
    dimensions;
    duration;
    startTime;
    timePassed;
    calculation;
    easing;
    params;
    progress;
    begin;
    began;
    process;
    complete;
    completed;
    paused = false;
    reverse = false;

    START = 'start';
    END = 'end';
    ADD = 'add';
    SUB = 'sub';

    static defaults = {
        measure: 'px',
        duration: 400
    };

    static easing = {
        linear(timeFraction) {
            return timeFraction;
        },
        easeInElastic: (timeFraction, {amplitude = 1, frequency = 3, decay = 8}) => {
            return amplitude * Math.cos(frequency * timeFraction * 2 * Math.PI) / Math.exp(decay * (1 - timeFraction));
        },
        easeOutElastic: (timeFraction, {amplitude = 1, frequency = 3, decay = 8}) => {
            return 1 - Animation.easing.easeInElastic(timeFraction, {amplitude, frequency, decay});
        },
        easeOutBounce: (timeFraction) => {
            return  timeFraction < 1   / 2.75 ? 7.5625 *  timeFraction                  * timeFraction :
                    timeFraction < 2   / 2.75 ? 7.5625 * (timeFraction -= 1.5   / 2.75) * timeFraction + .75 :
                    timeFraction < 2.5 / 2.75 ? 7.5625 * (timeFraction -= 2.25  / 2.75) * timeFraction + .9375 :
                    7.5625 * (timeFraction -= 2.625 / 2.75) * timeFraction + .984375;
        },
        easeOutExpo: (timeFraction) => {
            // return 1 - !~~timeFraction * 2**(-10 * timeFraction);
            return 1 - !~~timeFraction * Math.pow(2, (-10 * timeFraction));
        },
    };

    static easing2d = {
        bezier: (timeFraction, points) => {
            if (points.length < 2) return points[0];
            let P = [];
            for (let i = 0; i < points.length - 1; ++i) {
                P[i] = [points[i][0] + (points[i + 1][0] - points[i][0]) * timeFraction,
                        points[i][1] + (points[i + 1][1] - points[i][1]) * timeFraction];
            }
            return Animation.easing2d.bezier(timeFraction, P);
        },
        quadBezier0: (timeFraction, points) => {
            return [(1 - points[0]) * timeFraction, (1 - points[1]) * timeFraction];
        },
        quadBezier1: (timeFraction, points) => {
            return [(points[0] - 1) * timeFraction, (points[1] - 1) * timeFraction];
        },
        cubicBezier: (timeFraction, points) => {
            // return bezier(f, [[0, 0], [p[0], p[1]], [p[2], p[3]], [1, 1]])
            return Animation.easing2d.bezier(timeFraction, [[0, 0], ...points, [1, 1]]);
        },
        easeIn: (timeFraction) => {
            return Animation.easing2d.quadBezier0(timeFraction, [.42, 0])
        },
    };

    constructor({el, }) {
        this.el = document.querySelector(el);
    }

    static splitCssValue(value) {
        let parts = String(value).match(/^([+-]?\d+|\d*\.\d+)([a-z]*|%)$/);
        return {
            value: Number(parts[1]) || 0,
            measure: parts[2] || Animation.defaults.measure
        };
    }

    calcFrameForDimension(time, dimension) {
        Object.entries(this.properties[dimension]).forEach((property, index) => {
            this.el.style[property] = startValues[index] + delta[index] * deformation + serializedEndValues[index][1];
        });
    }

    nextFrame(time) {
        this.timePassed = time - this.startTime;
        this.timePassed < this.duration
            ? this.requestId = requestAnimationFrame(this.nextFrame.bind(this))
            : this.timePassed = this.duration;
        let timeFraction = this.timePassed / this.duration,
            deformation = this.easing(timeFraction, this.params);
        for (let dimension = 0; dimension < this.dimensions; ++dimension) {
            this.calculation[dimension].forEach((calc) => {
                this.el.style[calc.property] =
                    calc[this.START].value + calc.delta() * deformation[dimension] + calc[this.END].measure;
            });
        }
        this.progress = timeFraction * 100;
        this.process(timeFraction, deformation);
    }

    animate(properties, {
        easing,
        params,
        duration = Animation.defaults.duration,
        timePassed = 0,
        begin = () => {},
        process = () => {},
        complete = () => {},
    }) {
        let computedStyle = getComputedStyle(this.el);
        this.duration = duration;
        this.timePassed = timePassed;
        this.calculation = [];
        this.process = process;
        this.params = params;

        if (Array.isArray(properties)) {
            this.dimensions = properties.length;
            this.easing = easing;
        } else {
            properties = [properties];
            this.dimensions = 1;
            this.easing = (timeFraction, params) => [easing(timeFraction, params)];
        }

        properties.forEach((propertiesInDimension, dimension) => {
            this.calculation.push([]);
            Object.entries(propertiesInDimension).forEach((property) => {
                let distance = property[1],
                    auto = Animation.splitCssValue(computedStyle[property[0]]),
                    calc = {
                        property: property[0],
                    };
                if (distance === Object(distance)) {
                    calc.start = distance.start !== undefined && distance.start !== null
                        ? Animation.splitCssValue(distance.start)
                        : auto;
                    calc.end = distance.end !== undefined && distance.end !== null
                        ? Animation.splitCssValue(distance.end)
                        : auto;
                } else {
                    calc.start = Animation.splitCssValue(computedStyle[property[0]]);
                    calc.end = Animation.splitCssValue(distance);
                }
                calc.delta = () => (calc[this.END].value - calc[this.START].value);
                this.calculation[dimension].push(calc);
            });
        })
    }

    play({reverse = false} = {}) {
        this.reverse = reverse;
        if (reverse) {
            this.START = 'end';
            this.END = 'start';
        } else {
            this.START = 'start';
            this.END = 'end';
        }
        this.paused = false;
        this.requestId = requestAnimationFrame((time) => {
            // this.duration += this.startTime - time;
            this.startTime = time;
            this.nextFrame(time);
        });
    }
}

// let animation = new Animation({el: '.test'});
// animation.play([
//     {
//         marginTop: 25
//     },
//     {
//         height: 100
//     }
// ], {
//     duration: Infinity,
//     easing: 'bezier',
//     params: [[0, 0], [.42, 0], [.58, 1], [1, 1]],
//     dimension: 0
// });

setTimeout(() => {
    let animation = new Animation({el: '.popup'});

    // animation.animat({from: 50, to: 100}, {
    //     duration: 400,
    //     easing: Animation.easing2d.cubicBezier,
    //     params: [[.42, 0], [.58, 1]],
    //     process(startValue, valueFraction) {
    //         el.style.marginTop = startValue + valueFraction + 'px';
    //     }
    // });
    //
    // animation.animate([
    //     {maxWidth: 750},
    //     {height: 450}
    // ], {
    //     round: 1,
    //     loop: 3,
    //     duration: 300,
    //     easing: Animation.easing2d.cubicBezier,
    //     params: [[.42, 0], [.58, 1]],
    //     process() {
    //         console.log(this.progress);
    //     }
    // });

    // animation.play();

    console.log(animation);
}, 1000);

export default Animation;


// var curve = new CurveVisualizer(document.getElementById('myCanvas'));
